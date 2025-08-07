<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_dues', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_student_dues', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_dues');
        Schema::dropIfExists('_history_student_dues');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('student_id')->nullable();
        $table->bigInteger('due_id')->nullable();
        $table->double('price')->nullable();
        $table->bigInteger('school_year_id')->nullable();
        $table->string('is_active')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
