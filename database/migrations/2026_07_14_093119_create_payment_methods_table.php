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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('en_name', 50)->unique();
            $table->string('bn_name', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0=inactive, 1=active');
            $table->tinyInteger('is_system')->default(0)->comment('0=custom, 1=system_default');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
