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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('body');
            $table->enum('category', ['exam', 'holiday', 'fee', 'general'])->default('general');
            $table->json('audience')->nullable()->comment('["all"] or ["students", "class_3", etc]');
            $table->string('attachment_path')->nullable();
            $table->tinyInteger('is_pinned')->comment('0=no, 1=yes')->default(0);
            $table->tinyInteger('is_published')->comment('0=no, 1=yes')->default(1);
            $table->timestamp('publish_from')->nullable();
            $table->timestamp('publish_to')->nullable();
            $table->tinyInteger('is_sms_sent')->comment('0=no, 1=yes')->default(0);
            $table->tinyInteger('is_email_sent')->comment('0=no, 1=yes')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
