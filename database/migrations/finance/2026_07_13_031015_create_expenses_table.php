<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * payment_method: 0=cash, 1=bank, 2=bkash
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no', 30)->unique();
            $table->string('expense_head', 150)->index();
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('payment_method');   // 0=cash, 1=bank, 2=bkash
            $table->string('paid_to', 150)->nullable();
            $table->string('receipt_path', 255)->nullable();
            $table->date('expense_date')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
