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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('amount_paid');
        });

        // 2. Map existing data (0=cash,1=bkash,2=nagad,3=rocket,4=sslcommerz,5=bank,6=cheque)
        $methods = [0 => 'Cash', 1 => 'Bkash', 2 => 'Nagad', 3 => 'Rocket', 4 => 'SSLCommerz', 5 => 'Bank', 6 => 'Cheque'];
        foreach ($methods as $oldValue => $enName) {
            $method = DB::table('payment_methods')->where('en_name', $enName)->first();
            if ($method) {
                DB::table('payments')->where('payment_method', $oldValue)->update(['payment_method_id' => $method->id]);
            }
        }

        // 3. Fallback to Cash
        $cashMethod = DB::table('payment_methods')->where('en_name', 'Cash')->first();
        if ($cashMethod) {
            DB::table('payments')->whereNull('payment_method_id')->update(['payment_method_id' => $cashMethod->id]);
        }

        // 4. Drop old column and add constraint
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->tinyInteger('payment_method')->default(0)->after('amount_paid');
        });

        DB::table('payments')->update(['payment_method' => 0]);

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method_id');
        });
    }
};
