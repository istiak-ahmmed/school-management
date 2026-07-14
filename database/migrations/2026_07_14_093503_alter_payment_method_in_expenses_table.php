<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add the new column (nullable initially)
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('amount');
        });

        // 2. Map existing data
        $methods = [
            0 => 'Cash',
            1 => 'Bank',
            2 => 'Bkash',
        ];

        foreach ($methods as $oldValue => $enName) {
            $method = DB::table('payment_methods')->where('en_name', $enName)->first();
            if ($method) {
                DB::table('expenses')->where('payment_method', $oldValue)->update(['payment_method_id' => $method->id]);
            }
        }

        // 3. Fallback for any unmapped data to 'Cash'
        $cashMethod = DB::table('payment_methods')->where('en_name', 'Cash')->first();
        if ($cashMethod) {
            DB::table('expenses')->whereNull('payment_method_id')->update(['payment_method_id' => $cashMethod->id]);
        }

        // 4. Drop old column and add foreign key constraint
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            // Re-apply the constraint properly
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->tinyInteger('payment_method')->default(0)->after('amount');
        });

        // Reverse map data (assuming 0=Cash)
        DB::table('expenses')->update(['payment_method' => 0]);

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
};
