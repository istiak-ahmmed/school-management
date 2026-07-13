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
        // Alter Teachers Table
        Schema::table('teachers', function (Blueprint $table) {
            $table->json('qualification')->nullable()->change();
            $table->json('bank_account')->nullable()->change();
            
            // Rename bkash_number to mfs_account and make it JSON
            // We first rename it if it exists
            $table->renameColumn('bkash_number', 'mfs_account');
        });
        
        Schema::table('teachers', function (Blueprint $table) {
            // After renaming, change type to json
            $table->json('mfs_account')->nullable()->change();
        });

        // Alter Staff Table to match the Teachers table
        Schema::table('staff', function (Blueprint $table) {
            // Add NID, Qualification, Contract Type, Bank Account, and MFS Account
            $table->string('nid', 20)->nullable()->after('photo_path');
            $table->tinyInteger('contract_type')->default(1)->comment('1=permanent, 2=contractual, 3=part_time')->after('nid');
            $table->json('qualification')->nullable()->after('contract_type');
            $table->json('bank_account')->nullable()->after('qualification');
            $table->json('mfs_account')->nullable()->after('bank_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('qualification', 200)->nullable()->change();
            $table->string('bank_account', 50)->nullable()->change();
            
            $table->renameColumn('mfs_account', 'bkash_number');
        });
        
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('bkash_number', 15)->nullable()->change();
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['nid', 'contract_type', 'qualification', 'bank_account', 'mfs_account']);
        });
    }
};
