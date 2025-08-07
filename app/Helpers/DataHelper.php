<?php

namespace App\Helpers;

use App\Models\Classroom;
use App\Models\Maintenance;
use App\Models\MaintenanceApproval;
use App\Models\Sales;
use App\Models\SalesCancelApproval;
use App\Models\Student;
use App\Models\StudentClassroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use RuntimeException;

class DataHelper
{
    public static $LIKE = "ilike";
    public static function get_raw_number($data, $comma_separator = ".")
    {
        $prefix = "";
        // Jika data yang diberikan berisikan null langsung return nilai 0
        // $data = trim($data);

        $data = str_replace("rp.", "", strtolower($data));
        $data = str_replace(" ", "", strtolower($data));

        // Jika setelah data dibersihkan ternyata isinya null atau kosong
        if ($data == null || $data == '') return 0;

        // Jika setelah dibersihkan diawal data mengandung simbol -
        if ($data[0] == "-") $prefix = "-";

        // Pecah jadi 2 bagian, bagian depan koma dan belakang koma
        $price_value = 0;
        $comma_value = 0;

        $number_array = explode($comma_separator, $data);
        if (count($number_array) > 0) {
            $price_value = preg_replace("/[^0-9]/", "", $number_array[0]);
            $price_value = $price_value == null ? 0 : $price_value;
            $price_value = $prefix . $price_value;
            if ($price_value == "-0") {
                $price_value = 0;
            }
        }
        if (count($number_array) > 1) {
            $comma_value = preg_replace("/[^0-9]/", "", $number_array[1]);
            $comma_value = $comma_value == null ? 0 : $comma_value;
        }

        return ($comma_value == 0) ?  $price_value : $price_value . "." . $comma_value;
    }


    public static function format_indonesia_date($date, $with_day = false)
    {
        return $with_day ? Carbon::parse($date)->isoFormat('dddd, D MMMM Y') : Carbon::parse($date)->isoFormat('D MMMM Y');
    }

    public function idr_currency_factory($value)
    {
        $value = abs($value);
        $word = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($value < 12) {
            $temp = " " . $word[$value];
        } else if ($value < 20) {
            $temp = $this->idr_currency_factory($value - 10) . " belas";
        } else if ($value < 100) {
            $temp = $this->idr_currency_factory($value / 10) . " puluh" . $this->idr_currency_factory($value % 10);
        } else if ($value < 200) {
            $temp = " seratus" . $this->idr_currency_factory($value - 100);
        } else if ($value < 1000) {
            $temp = $this->idr_currency_factory($value / 100) . " ratus" . $this->idr_currency_factory($value % 100);
        } else if ($value < 2000) {
            $temp = " seribu" . $this->idr_currency_factory($value - 1000);
        } else if ($value < 1000000) {
            $temp = $this->idr_currency_factory($value / 1000) . " ribu" . $this->idr_currency_factory($value % 1000);
        } else if ($value < 1000000000) {
            $temp = $this->idr_currency_factory($value / 1000000) . " juta" . $this->idr_currency_factory($value % 1000000);
        } else if ($value < 1000000000000) {
            $temp = $this->idr_currency_factory($value / 1000000000) . " milyar" . $this->idr_currency_factory(fmod($value, 1000000000));
        } else if ($value < 1000000000000000) {
            $temp = $this->idr_currency_factory($value / 1000000000000) . " trilyun" . $this->idr_currency_factory(fmod($value, 1000000000000));
        }
        return $temp;
    }

    public static function get_month_name($num)
    {
        $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return $months[$num];
    }

    public static function whatsapp_phone_number_formatter($raw_phone_number)
    {
        if ($raw_phone_number == null) return null;
        
        $prefix = "62";
        $phone_number = preg_replace('/\D/', '', $raw_phone_number);
        
        // Untuk case nomor telepon tidak diawal dengan 0
        if (strlen($phone_number) >= 2) {

            // Jika sudah format 62
            if (substr($phone_number, 0, 2) == '62') {
                return $phone_number;
            }

            // Jika index awal bukan 0
            if ($phone_number[0] != "0") {
                $phone_number = $prefix . $phone_number;
            }
        }

        // Jika index awal adalah 0
        if (substr($phone_number, 0, 1) === '0') {
            // Ganti 0 di awal dengan kode negara 62
            $phone_number = $prefix . substr($phone_number, 1);
        }
        
        return $phone_number;
    }

    public static function get_classroom_grade_va_code($str) {
        if ($str == null) return "04";
        if (strtolower($str) == "tk") return "00";
        if (strtolower($str) == "sd") return "01";
        if (strtolower($str) == "smp") return "02";
    }

    public static function re_init_student_va() {
        $students = Student::whereNull("deleted_at")->get();

        // Update student classroom
        $student_classrooms = StudentClassroom::where("is_active", "=", 1)->get();
        foreach ($student_classrooms as $student_classroom) {
            foreach ($students as $student) {
                if ($student->id == $student_classroom->student_id) {
                    $classroom = Classroom::find($student_classroom->classroom_id);
                    $student->backtrack_current_classroom_id = $classroom->id;
                    $student->backtrack_current_classroom_name = $classroom->name;
                    $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($classroom->school_group);
                    $student->save();
                    echo "Student " . $student->id . " updated<br>";
                }
            }
        }

        foreach ($students as $student) {
            $classroom = Classroom::find($student->backtrack_current_classroom_id);
            if ($classroom != null) {
                $student->backtrack_class_grade = DataHelper::get_classroom_grade_va_code($classroom->school_group);
                $student->save();
                echo "Student " . $student->id . " updated<br>";
            }
        }
    }

}
