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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 20)->nullable();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->tinyInteger('subject_type')->default(1)->comment('1=core, 2=optional, 3=extra')->default(1);
            $table->integer('full_marks')->default(100);
            $table->integer('pass_marks')->default(33);
            $table->tinyInteger('is_active')->default(1)->comment('0=inactive, 1=active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
