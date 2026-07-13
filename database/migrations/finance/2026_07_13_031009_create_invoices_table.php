<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * status: 0=unpaid, 1=partial, 2=paid, 3=waived, 4=cancelled
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('fee_type_id')->nullable()->constrained('fee_types')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('month_year', 10)->nullable()->index();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->date('due_date')->nullable();
            $table->tinyInteger('status')->default(0)->index(); // 0=unpaid,1=partial,2=paid,3=waived,4=cancelled
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
