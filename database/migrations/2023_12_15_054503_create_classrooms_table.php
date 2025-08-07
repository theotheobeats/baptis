<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_classrooms', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('_history_classrooms');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string('school_group')->nullable()->comment("TK, SD, SMP");
        $table->string('grade')->nullable()->comment("Kelompok Kelas PG, TK A, TK B, 1, 2, 3, 4, 5, 6, 7, 8, 9");
        $table->string('name')->nullable();
        $table->string('note')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
