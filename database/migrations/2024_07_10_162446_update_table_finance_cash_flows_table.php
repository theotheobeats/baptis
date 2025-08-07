<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('finance_cash_flows', function (Blueprint $table) {
            $table->string('source')->nullable()->after('id')->nullable();
            $table->dateTime('verified_at')->after('credit')->nullable();
            $table->bigInteger('verified_by')->after('verified_at')->nullable();
        });

        Schema::table('_history_finance_cash_flows', function (Blueprint $table) {
            $table->string('source')->nullable()->after('id')->nullable();
            $table->dateTime('verified_at')->after('credit')->nullable();
            $table->bigInteger('verified_by')->after('verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('finance_cash_flows', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->dropColumn('verified_at');
            $table->dropColumn('verified_by');
        });

        Schema::table('_history_finance_cash_flows', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->dropColumn('verified_at');
            $table->dropColumn('verified_by');
        });
    }
};
