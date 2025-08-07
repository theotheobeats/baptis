<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\PositionImport;
use App\Imports\TeacherImport;
use App\Models\Teacher;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class TeacherController extends Controller
{

    public static $information = [
        "title" => "Master Guru",
        "route" => "/master/teacher",
        "view" => "pages.master.teacher."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $teachers = new Teacher();
            $teachers = $teachers->select("teachers.*");
            return DataTables::of($teachers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('updated_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s'); return $formatedDate; })
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
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }

    
    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $teacher = Teacher::find($decrypt->id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "teacher" => $teacher
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        $result = Teacher::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    
    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;
        
        $result = Teacher::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    
    // Proses hapus data
    public function destroy($id)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;
        
        $result = Teacher::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request) {
        $teachers = Teacher::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Nama'],
            ['text' => 'Telepon'],
        ];
        foreach ($teachers as $teacher) {
            $result[] = [
                ['text' => $teacher->id],
                ['text' => $teacher->name],
                ['text' => $teacher->phone],
            ];
        }
        return response()->json($result);
    }
    
    public function import_excel(Request $request) {
        Excel::import(new TeacherImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }

}
