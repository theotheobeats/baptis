<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_cash_flows', function (Blueprint $table) {
            $table->string('coa_sub_detail_name')->nullable()->comment('nama dari coa')->after('account_id');
            $table->string('sub_detail')->nullable()->comment('input dari arus kas')->after('coa_sub_detail_name');
        });

        Schema::table('_history_finance_cash_flows', function (Blueprint $table) {
            $table->string('coa_sub_detail_name')->nullable()->comment('nama dari coa')->after('account_id');
            $table->string('sub_detail')->nullable()->comment('input dari arus kas')->after('coa_sub_detail_name');
        });
    }

    public function down(): void
    {
        Schema::table('finance_cash_flows', function (Blueprint $table) {
            $table->dropColumn('coa_sub_detail_name');
            $table->dropColumn('sub_detail');
        });

        Schema::table('_history_finance_cash_flows', function (Blueprint $table) {
            $table->dropColumn('coa_sub_detail_name');
            $table->dropColumn('sub_detail');
        });
    }
};
