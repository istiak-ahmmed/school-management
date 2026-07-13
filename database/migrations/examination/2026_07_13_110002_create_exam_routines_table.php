<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->date('exam_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('room', 50)->nullable();
            $table->integer('full_marks')->default(100);
            $table->integer('pass_marks')->default(33);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_routines');
    }
};
