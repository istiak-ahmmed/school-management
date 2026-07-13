<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * employee_type: 0=teacher, 1=staff
     */
    public function up(): void
    {
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_type');            // 0=teacher, 1=staff
            $table->unsignedBigInteger('employee_id')->index();
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('house_rent', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('deduction_provident', 10, 2)->default(0);
            $table->decimal('deduction_tax', 10, 2)->default(0);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index(['employee_type', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
