<?php

namespace App\Console\Commands;

use App\Enums\InvoiceStatus;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyFees extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fees:generate-monthly
                            {--month= : Month to generate for (Y-m format, e.g. 2025-09). Defaults to current month.}
                            {--force : Regenerate even if some invoices already exist}';

    /**
     * The console command description.
     */
    protected $description = 'Generate monthly fee invoices for all active students based on their class fee structures.';

    public function handle(): int
    {
        $monthYear = $this->option('month') ?? Carbon::now()->format('Y-m');

        // Validate format
        try {
            $month = Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
        } catch (\Exception $e) {
            $this->error("Invalid month format: {$monthYear}. Use Y-m e.g. 2025-09");
            return Command::FAILURE;
        }

        $dueDate = $month->copy()->day(10); // Due by 10th of each month

        // Step 1: Get current academic year
        $academicYear = AcademicYear::where('is_current', 1)->first();
        if (! $academicYear) {
            $this->error('কোনো সক্রিয় শিক্ষাবর্ষ পাওয়া যায়নি। প্রথমে একটি শিক্ষাবর্ষ সক্রিয় করুন।');
            return Command::FAILURE;
        }

        $this->info("শিক্ষাবর্ষ: {$academicYear->name}");
        $this->info("মাস: {$monthYear} | বকেয়া তারিখ: {$dueDate->format('d M Y')}");

        // Step 2: Get all active students with their class
        $students = Student::where('status', 1)
            ->whereNotNull('class_id')
            ->select('id', 'class_id', 'academic_year_id')
            ->get();

        if ($students->isEmpty()) {
            $this->warn('কোনো সক্রিয় শিক্ষার্থী পাওয়া যায়নি।');
            return Command::SUCCESS;
        }

        $this->info("মোট সক্রিয় শিক্ষার্থী: {$students->count()}");

        // Step 3: Load all fee structures for this academic year (recurring monthly fees)
        $feeStructures = FeeStructure::where('academic_year_id', $academicYear->id)
            ->whereHas('feeType', fn ($q) => $q->where('is_recurring', 1)->where('is_active', 1)->where('frequency', 1)) // monthly
            ->with('feeType')
            ->get()
            ->groupBy('class_id');  // keyed by class_id

        $created    = 0;
        $skipped    = 0;
        $lastId     = Invoice::max('id') ?? 0;
        $year       = Carbon::now()->year;

        $this->withProgressBar($students, function (Student $student) use (
            $feeStructures, $monthYear, $dueDate, $academicYear,
            &$created, &$skipped, &$lastId, $year
        ) {
            // Get fee structures for this student's class (class-specific + null = all classes)
            $structures = collect();

            if (isset($feeStructures[$student->class_id])) {
                $structures = $structures->merge($feeStructures[$student->class_id]);
            }
            // Also apply structures that have NULL class_id (school-wide fees)
            if (isset($feeStructures[null])) {
                $structures = $structures->merge($feeStructures[null]);
            }

            foreach ($structures as $structure) {
                // Step 4: Check if invoice already exists for this month
                $exists = Invoice::where('student_id', $student->id)
                    ->where('fee_type_id', $structure->fee_type_id)
                    ->where('month_year', $monthYear)
                    ->exists();

                if ($exists && ! $this->option('force')) {
                    $skipped++;
                    continue;
                }

                $lastId++;
                $invoiceNo = 'INV-' . $year . '-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);

                DB::transaction(function () use (
                    $student, $structure, $monthYear, $dueDate,
                    $academicYear, $invoiceNo
                ) {
                    Invoice::create([
                        'invoice_no'       => $invoiceNo,
                        'student_id'       => $student->id,
                        'fee_type_id'      => $structure->fee_type_id,
                        'academic_year_id' => $academicYear->id,
                        'month_year'       => $monthYear,
                        'amount'           => $structure->amount,
                        'discount'         => 0,
                        'fine'             => 0,
                        'net_amount'       => $structure->amount,
                        'due_date'         => $dueDate,
                        'status'           => InvoiceStatus::Unpaid,
                    ]);
                });

                $created++;
            }
        });

        $this->newLine(2);
        $this->info("✅ সম্পন্ন! {$created}টি ইনভয়েস তৈরি হয়েছে। {$skipped}টি এড়িয়ে গেছে (ইতিমধ্যে বিদ্যমান)।");

        return Command::SUCCESS;
    }
}
