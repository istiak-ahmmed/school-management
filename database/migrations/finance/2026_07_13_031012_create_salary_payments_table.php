<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * employee_type: 0=teacher, 1=staff
     * payment_method: 0=cash, 1=bank, 2=bkash
     * status: 0=paid, 1=pending, 2=partial
     */
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no', 30)->unique();
            $table->tinyInteger('employee_type');             // 0=teacher, 1=staff
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('month_year', 10)->index();
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->decimal('total_allowance', 10, 2)->default(0);
            $table->decimal('total_deduction', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2)->nullable();
            $table->decimal('net_salary', 10, 2)->nullable();
            $table->decimal('advance_deducted', 10, 2)->default(0);
            $table->tinyInteger('payment_method');            // 0=cash, 1=bank, 2=bkash
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('status')->default(1)->index(); // 0=paid, 1=pending, 2=partial
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['employee_type', 'employee_id', 'month_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
