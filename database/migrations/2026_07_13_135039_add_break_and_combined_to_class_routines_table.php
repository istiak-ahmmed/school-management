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
        Schema::table('class_routines', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->change();
            $table->unsignedBigInteger('teacher_id')->nullable()->change();
            
            $table->tinyInteger('is_break')->default(0)->after('room');
            $table->tinyInteger('is_combined')->default(0)->after('is_break');
            $table->string('note')->nullable()->after('is_combined');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_routines', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable(false)->change();
            $table->unsignedBigInteger('teacher_id')->nullable(false)->change();
            
            $table->dropColumn(['is_break', 'is_combined', 'note']);
        });
    }
};
