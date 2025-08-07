<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_accounts', function (Blueprint $table) {
            $table->boolean('hide_coa')->default(false)->after("description");
        });

        Schema::table('_history_finance_accounts', function (Blueprint $table) {
            $table->boolean('hide_coa')->default(false)->after("description");
        });
    }

    public function down(): void
    {
        Schema::table('finance_accounts', function (Blueprint $table) {
            $table->dropColumn('hide_coa');
        });

        Schema::table('_history_finance_accounts', function (Blueprint $table) {
            $table->dropColumn('hide_coa');
        });
    }
};
