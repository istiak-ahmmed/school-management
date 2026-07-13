<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->integer('full_marks')->default(100);
            $table->integer('pass_marks')->default(33);
            $table->tinyInteger('is_absent')->default(0)->comment('0=Present, 1=Absent');
            $table->string('grade', 5)->nullable();
            $table->decimal('grade_point', 3, 1)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('entered_at')->nullable();
            $table->timestamps();

            $table->unique(['exam_id', 'student_id', 'subject_id'], 'unique_mark');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
