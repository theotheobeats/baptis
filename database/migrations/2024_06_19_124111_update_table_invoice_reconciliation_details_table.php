<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_reconciliation_details', function (Blueprint $table) {
            $table->bigInteger('created_by')->after('id')->nullable();
            $table->bigInteger('updated_by')->after('id')->nullable();
            $table->bigInteger('deleted_by')->after('id')->nullable();
        });

        Schema::table('_history_invoice_reconciliation_details', function (Blueprint $table) {
            $table->bigInteger('created_by')->after('id')->nullable();
            $table->bigInteger('updated_by')->after('id')->nullable();
            $table->bigInteger('deleted_by')->after('id')->nullable();
        });
    }
};
