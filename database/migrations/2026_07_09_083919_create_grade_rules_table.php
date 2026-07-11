<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grade_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->cascadeOnDelete();
            $table->decimal('min_marks', 5, 2);
            $table->decimal('max_marks', 5, 2);
            $table->string('grade', 5);
            $table->decimal('grade_point', 3, 1);
            $table->string('remarks', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_rules');
    }
};
