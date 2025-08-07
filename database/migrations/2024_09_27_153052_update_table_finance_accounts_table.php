<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('finance_accounts', function (Blueprint $table) {
            $table->string('sub_detail')->after('name')->nullable();
        });

        Schema::table('_history_finance_accounts', function (Blueprint $table) {
            $table->string('sub_detail')->after('name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('finance_accounts', function (Blueprint $table) {
            $table->dropColumn('sub_detail');
        });

        Schema::table('_history_finance_accounts', function (Blueprint $table) {
            $table->dropColumn('sub_detail');
        });
    }
};
