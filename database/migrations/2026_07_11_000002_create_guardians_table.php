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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Guardian user account if they have login');
            $table->string('name', 150)->comment('Father/Guardian name');
            $table->string('phone', 15)->unique()->index();
            $table->string('email', 191)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('relation', 50)->default('father')->comment('father, mother, guardian');
            $table->string('mother_name', 150)->nullable();
            $table->string('mother_occupation', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
