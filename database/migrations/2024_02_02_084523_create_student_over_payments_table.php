<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_overpayments', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_student_overpayments', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_overpayments');
        Schema::dropIfExists('_history_student_overpayments');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('student_id')->nullable();
        $table->bigInteger('ref_id')->nullable();
        $table->string('ref_table')->nullable();
        $table->double('price')->nullable();
        $table->double('claimed_price')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
