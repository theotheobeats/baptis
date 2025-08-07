<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_active_histories', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_student_active_histories', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_active_histories');
        Schema::dropIfExists('_history_student_active_histories');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('student_id')->nullable();
        $table->datetime('active_at')->nullable();
        $table->datetime('non_active_at')->nullable();
        $table->bigInteger('actived_by')->nullable();
        $table->bigInteger('non_actived_by')->nullable();
        $table->text('note')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
