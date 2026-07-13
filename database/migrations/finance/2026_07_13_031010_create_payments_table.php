<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * payment_method: 0=cash, 1=bkash, 2=nagad, 3=rocket, 4=sslcommerz, 5=bank, 6=cheque
     * payment_status: 0=success, 1=pending, 2=failed, 3=refunded
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no', 30)->unique();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2);
            $table->tinyInteger('payment_method');           // 0=cash,1=bkash,2=nagad,3=rocket,4=sslcommerz,5=bank,6=cheque
            $table->string('transaction_id', 100)->nullable();
            $table->json('gateway_response')->nullable();
            $table->tinyInteger('payment_status')->default(0)->index(); // 0=success,1=pending,2=failed,3=refunded
            $table->string('paid_by', 150)->nullable();
            $table->timestamp('paid_at');
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('receipt_generated')->default(0); // 0=no, 1=yes
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
