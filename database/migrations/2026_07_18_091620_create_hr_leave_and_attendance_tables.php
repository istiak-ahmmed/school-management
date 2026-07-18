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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('max_days_per_year')->default(10);
            $table->boolean('is_paid')->default(true);
            $table->enum('applicable_to', ['teacher', 'staff', 'both'])->default('both');
            $table->timestamps();
        });

        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->enum('employee_type', ['teacher', 'staff']);
            $table->unsignedBigInteger('employee_id');
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('total_days', 5, 1);
            $table->boolean('is_half_day')->default(false);
            $table->text('reason')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->enum('employee_type', ['teacher', 'staff']);
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->tinyInteger('status')->comment('1=Present, 2=Absent, 3=Late, 4=Leave, 5=Half Day');
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('leave_types');
    }
};
