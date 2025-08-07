<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_students', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('_history_students');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->string('nis')->nullable();
        $table->string('nisn')->nullable();
        $table->string('name')->nullable();
        $table->enum('gender', ["Male", "Female"])->nullable();
        $table->date('birth_date')->nullable();
        $table->string('birth_place')->nullable();
        $table->enum('religion', ["Kristen", "Buddha", "Islam", "Katolik", "Hindu", "Kong Hu Cu"])->nullable();
        $table->string('address')->nullable();
        $table->string('phone')->nullable();
        $table->string('father_name')->nullable();
        $table->string('father_phone')->nullable();
        $table->string('father_address')->nullable();
        $table->string('father_religion')->nullable();
        $table->string('father_job')->nullable();
        $table->string('mother_name')->nullable();
        $table->string('mother_phone')->nullable();
        $table->string('mother_address')->nullable();
        $table->string('mother_religion')->nullable();
        $table->string('mother_job')->nullable();
        $table->string('rt')->nullable();
        $table->string('rw')->nullable();
        $table->bigInteger('village_id')->nullable();
        $table->bigInteger('district_id')->nullable();
        $table->string('postal_code')->nullable();
        $table->string('parent_contact')->nullable();
        $table->datetime('non_active_at')->nullable();
        
        $table->string('backtrack_current_classroom_id')->nullable()->comment("ID kelas saat ini");
        $table->string('backtrack_current_classroom_name')->nullable()->comment("Default nama kelas");
        $table->string('backtrack_student_whatsapp_number')->nullable()->comment("Nomor untuk notifikasi whatsapp");
        
        $table->bigInteger('non_active_by')->nullable();
        $table->bigInteger('created_by')->nullable();
        $table->bigInteger('updated_by')->nullable();
        $table->bigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }
};
