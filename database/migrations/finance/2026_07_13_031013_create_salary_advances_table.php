<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * employee_type: 0=teacher, 1=staff
     * status: 0=pending, 1=approved, 2=rejected, 3=fully_recovered
     */
    public function up(): void
    {
        Schema::create('salary_advances', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_type');             // 0=teacher, 1=staff
            $table->unsignedBigInteger('employee_id')->index();
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->tinyInteger('recovery_months')->default(1);
            $table->decimal('recovered_amount', 10, 2)->default(0);
            $table->tinyInteger('status')->default(0)->index(); // 0=pending,1=approved,2=rejected,3=fully_recovered
            $table->timestamps();

            $table->index(['employee_type', 'employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_advances');
    }
};
