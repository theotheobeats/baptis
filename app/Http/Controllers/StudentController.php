<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\StudentImport;
use App\Models\AddressDistrict;
use App\Models\AddressVillage;
use App\Models\Bank;
use App\Models\Classroom;
use App\Models\Due;
use App\Models\SchoolYear;
use App\Models\StudentClassroom;
use App\Models\StudentDue;
use App\Models\StudentVaAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{

    public static $information = [
        "title" => "Master Siswa",
        "route" => "/master/student",
        "view" => "pages.master.student."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("student", "view")) return abort(404);
        if ($request->ajax()) {
            $students = new Student();
            $students = $students->select("students.*");

            if ($request->student_status != "") {
                if ($request->student_status == "active") {
                    $students = $students->whereNull("students.non_active_at");
                } else {
                    $students = $students->where("students.non_active_at", "!=", null);
                }
            }

            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $active_action = 'active_confirm("' . url(self::$information['route'] . '/activate') . '/' . $encrypted_id . '")';
                    $deactive_action = 'deactive_confirm("' . url(self::$information['route'] . '/deactivate') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    if ($row->non_active_at == null) {
                        $btn .= "<a class='btn btn-outline-sucess' href='#' onclick='$deactive_action' title='Non Aktifkan Siswa'><i class='fa fa-toggle-on'></i></a>";
                    } else {
                        $btn .= "<a class='btn btn-outline-secondary' href='#' onclick='$active_action' title='Aktifkan Siswa'><i class='fa fa-toggle-off'></i></a>";
                    }
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('birth_date', function ($data) {
                    if ($data->birth_date == null) return null;
                    $formatedDate = Carbon::createFromFormat('Y-m-d', $data->birth_date)->translatedFormat('d F Y');
                    return $formatedDate;
                })
                ->editColumn('updated_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form input data
    public function create()
    {
        if (!UserInfoHelper::has_access("student", "add")) return abort(404);
        $banks = Bank::select("id", "name")->get();
        $dues = Due::select("id", "name")->get();
        $villages = AddressVillage::select("id", "name")->get();
        $districts = AddressDistrict::select("id", "name")->get();
        $school_years = SchoolYear::select("id", "name", "semester", "is_active")->get();

        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
            "banks" => $banks,
            "villages" => $villages,
            "districts" => $districts,
            "dues" => $dues,
            "school_years" => $school_years
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("student", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $student = Student::find($decrypt->id);
        $dues = Due::select("id", "name")->get();
        $banks = Bank::select("id", "name")->get();
        $student_va = StudentVaAccount::where("student_id", "=", $student->id)->get();
        $student_dues = StudentDue::join("dues", "dues.id", "=", "student_dues.due_id")
            ->where("student_id", "=", $student->id)
            ->select("student_dues.*", "dues.name as due_name")
            ->get();

        $villages = AddressVillage::select("id", "name")->get();
        $districts = AddressDistrict::select("id", "name")->get();

        // $classroom = Classroom::find($student->backtrack_current_classroom_id);
        $student_classroom = StudentClassroom::where("student_id", "=", $student->id)->where("is_active", "=", 1)->first();
        $classroom = Classroom::find($student_classroom->classroom_id);
        $school_year = SchoolYear::find($student_classroom->school_year_id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "student" => $student,
            "banks" => $banks,
            "student_dues" => $student_dues,
            "student_va" => $student_va,
            "villages" => $villages,
            "districts" => $districts,
            "classroom" => $classroom,
            "school_year" => $school_year
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("student", "add")) return abort(404);
        $result = Student::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("student", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Student::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("student", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Student::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("student", "export")) return abort(404);
        $student_status = $request->student_status;
        //$dues = Due::get();
        $students = new Student;
        if ($student_status == "active") {
            $students = $students->whereNull('non_active_at');
        }
        if ($student_status == "deactive") {
            $students = $students->whereNotNull('non_active_at');
        }
        $students = $students->get();
        
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'NIS'],
            ['text' => 'NISN'],
            ['text' => 'Nama'],
            ['text' => 'Jenis Kelamin'],
            ['text' => 'Tanggal Lahir'],
            ['text' => 'Tempat Lahir'],
            ['text' => 'Agama'],
            ['text' => 'Alamat'],
            ['text' => 'Nomor Telepon'],
            ['text' => 'Nama Ayah'],
            ['text' => 'Kontak Ayah'],
            ['text' => 'Alamat Ayah'],
            ['text' => 'Agama Ayah'],
            ['text' => 'Pekerjaan Ayah'],
            ['text' => 'Nama Ibu'],
            ['text' => 'Kontak Ibu'],
            ['text' => 'Alamat Ibu'],
            ['text' => 'Agama Ibu'],
            ['text' => 'Pekerjaan Ibu'],
            ['text' => 'Nomor Telepon Orang Tua'],
            ['text' => 'Nomor WA Notifikasi'],
            ['text' => 'RT'],
            ['text' => 'RW'],
            ['text' => 'Kelurahan'],
            ['text' => 'Kecamatan'],
            ['text' => 'Kode Pos'],
            ['text' => 'Kelas'],
        ];

        // foreach ($dues as $due) {
        //     $result[0][] = ['text' => $due->name];
        // }

        foreach ($students as $student) {
            //$due_prices = [];

            $village = AddressVillage::find($student->village_id);
            $village_name = null;
            if ($village != null) {
                $village_name = $village->name;
            }

            $district = AddressDistrict::find($student->district_id);
            $district_name = null;
            if ($district != null) {
                $district_name = $district->name;
            }

            // $student_dues = StudentDue::where('student_dues.student_id', $student->id)->get();

            // foreach ($dues as $due) {
            //     $price = '';
            //     $student_due = $student_dues->where('due_id', $due->id)->first();

            //     if ($student_due !== null) {
            //         $price = $student_due->price;
            //     }

            //     $due_prices[] = ['text' => $price];
            // }

            $result[] = [
                ['text' => $student->id],
                ['text' => $student->nis],
                ['text' => $student->nisn],
                ['text' => $student->name],
                ['text' => $student->gender],
                ['text' => $student->birth_date],
                ['text' => $student->birth_place],
                ['text' => $student->religion],
                ['text' => $student->address],
                ['text' => $student->phone],
                ['text' => $student->father_name],
                ['text' => $student->father_phone],
                ['text' => $student->father_address],
                ['text' => $student->father_religion],
                ['text' => $student->father_job],
                ['text' => $student->mother_name],
                ['text' => $student->mother_phone],
                ['text' => $student->mother_address],
                ['text' => $student->mother_religion],
                ['text' => $student->mother_job],
                ['text' => $student->parent_contact],
                ['text' => $student->backtrack_student_whatsapp_number],
                ['text' => $student->rt],
                ['text' => $student->rw],
                ['text' => $village_name],
                ['text' => $district_name],
                ['text' => $student->postal_code],
                ['text' => $student->backtrack_current_classroom_name],
                //...$due_prices,
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("student", "import")) return abort(404);
        Excel::import(new StudentImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }

    public function due_bind($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Student::do_student_due_bind_store($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    // Pengaktifan Siswa
    public function student_activate($id, Request $request)
    {
        if (!UserInfoHelper::has_access("student", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Student::do_student_activate($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    // Penonaktifkan Siswa
    public function student_deactivate($id, Request $request)
    {
        if (!UserInfoHelper::has_access("student", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Student::do_student_deactivate($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
