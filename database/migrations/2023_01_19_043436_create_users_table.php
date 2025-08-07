<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_users', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('_history_users');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string('email', 100)->nullable();
        $table->string('username', 100)->nullable();
        $table->string('password', 200)->nullable();
        $table->string('pin')->nullable();
        $table->string('web_ip', 100)->nullable();
        $table->dateTime('logout_time')->nullable();
        $table->boolean('config_dark_theme')->default(0);
        $table->bigInteger('employee_id')->nullable()->comment('Id Pegawai');
        $table->text('access')->nullable();

        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
