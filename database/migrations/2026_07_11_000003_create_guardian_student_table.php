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
        Schema::create('guardian_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_id')->constrained('guardians')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('relation', 50)->default('father')->comment('Relation to the student');
            $table->boolean('is_primary')->default(false)->comment('Primary contact for the student');
            $table->timestamps();

            $table->unique(['guardian_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_student');
    }
};
