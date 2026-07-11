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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('employee_id', 20)->unique()->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->string('photo_path', 255)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=inactive, 1=active, 2=on_leave, 3=resigned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
