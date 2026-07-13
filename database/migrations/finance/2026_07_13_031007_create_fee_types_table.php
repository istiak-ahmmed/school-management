<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * frequency: 0=one_time, 1=monthly, 2=quarterly, 3=yearly
     */
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 20)->nullable();
            $table->tinyInteger('is_recurring')->default(0); // 0=no, 1=yes
            $table->tinyInteger('frequency')->default(0);   // 0=one_time,1=monthly,2=quarterly,3=yearly
            $table->tinyInteger('is_active')->default(1);   // 0=inactive, 1=active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_types');
    }
};
