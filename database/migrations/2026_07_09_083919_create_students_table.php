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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('admission_no', 30)->unique();
            $table->string('roll_no', 20)->nullable();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->date('date_birth')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1=male, 2=female, 3=other');
            $table->tinyInteger('blood_group')->nullable()->comment('1=A+, 2=B+, 3=O+, 4=A-, 5=B-, 6=O-, 7=AB+, 8=AB-');
            $table->tinyInteger('religion')->default(1)->comment('1=Islam');
            $table->string('nationality', 50)->default('Bangladeshi');
            $table->string('birth_certificate_no', 50)->nullable();
            $table->text('address_present')->nullable();
            $table->text('address_permanent')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->text('medical_info')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=inactive, 1=active, 2=passed_out, 3=expelled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
