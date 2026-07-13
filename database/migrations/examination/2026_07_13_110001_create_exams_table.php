<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 20)->nullable();
            $table->tinyInteger('exam_type')->default(0)->comment('0=Monthly, 1=HalfYearly, 2=Annual, 3=Test, 4=Special');
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('result_publish_date')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Upcoming, 1=Ongoing, 2=MarksEntry, 3=Published, 4=Archived');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
