<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add new column
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('advance_deducted');
        });

        // 2. Map existing data
        $methods = [0 => 'Cash', 1 => 'Bank', 2 => 'Bkash'];
        foreach ($methods as $oldValue => $enName) {
            $method = DB::table('payment_methods')->where('en_name', $enName)->first();
            if ($method) {
                DB::table('salary_payments')->where('payment_method', $oldValue)->update(['payment_method_id' => $method->id]);
            }
        }

        // 3. Fallback to Cash
        $cashMethod = DB::table('payment_methods')->where('en_name', 'Cash')->first();
        if ($cashMethod) {
            DB::table('salary_payments')->whereNull('payment_method_id')->update(['payment_method_id' => $cashMethod->id]);
        }

        // 4. Drop old column and add constraint
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->tinyInteger('payment_method')->default(0)->after('advance_deducted');
        });

        DB::table('salary_payments')->update(['payment_method' => 0]);

        Schema::table('salary_payments', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
};
