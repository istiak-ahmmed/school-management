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
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no', 30)->unique()->index();
            $table->string('applicant_name', 150);
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->foreignId('applying_for_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('guardian_name', 150)->nullable();
            $table->string('guardian_phone', 15)->nullable();
            $table->string('guardian_email', 191)->nullable();
            $table->text('address')->nullable();
            $table->string('previous_school', 200)->nullable();
            $table->json('documents_path')->nullable()->comment('Array of uploaded file paths');
            $table->tinyInteger('status')->default(1)->comment('1=pending, 2=under_review, 3=accepted, 4=rejected, 5=enrolled')->default(1)->index();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};
