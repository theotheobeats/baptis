<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_classrooms', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_student_classrooms', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_classrooms');
        Schema::dropIfExists('_history_student_classrooms');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('student_id')->nullable();
        $table->bigInteger('teacher_id')->nullable()->comment('Wali Kelas');
        $table->bigInteger('classroom_id')->nullable();
        $table->bigInteger('school_year_id')->nullable();
        $table->boolean('is_active')->default(0)->nullable()->comment('Aktif / Tidak Aktif');
        $table->string('status')->nullable()->comment('Pindah Kelas, Naik Kelas, ...');
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
