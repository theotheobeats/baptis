<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('backtrack_class_grade')->nullable()->comment("kode kelas untuk digunakan di nomor VA")->after('id')->nullable();
        });

        Schema::table('_history_students', function (Blueprint $table) {
            $table->string('backtrack_class_grade')->nullable()->comment("kode kelas untuk digunakan di nomor VA")->after('id')->nullable();
        });
    }
};
