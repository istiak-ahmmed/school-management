<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * account_type: 0=income, 1=expense, 2=asset, 3=liability
     * entry_type:   0=debit, 1=credit
     */
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('account_head', 150);
            $table->tinyInteger('account_type')->index(); // 0=income,1=expense,2=asset,3=liability
            $table->decimal('amount', 12, 2);
            $table->tinyInteger('entry_type');            // 0=debit, 1=credit
            $table->string('reference_type', 50)->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
