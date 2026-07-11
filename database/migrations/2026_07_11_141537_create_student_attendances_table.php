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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->date('date');
            $table->tinyInteger('status')->default(1)->comment('1=present, 2=absent, 3=late, 4=excused');
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date'], 'unique_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
