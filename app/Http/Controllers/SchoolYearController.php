<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\PositionImport;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SchoolYearController extends Controller
{

    public static $information = [
        "title" => "Master Tahun Ajaran",
        "route" => "/master/school-year",
        "view" => "pages.master.school-year."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("school_year", "view")) return abort(404);
        if ($request->ajax()) {
            $school_years = new SchoolYear();
            $school_years = $school_years->select("school_years.*");
            return DataTables::of($school_years)
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
                ->editColumn('is_active', function($data){ return $data->is_active == 1 ? "<span style='color: green'><b>Aktif</b></span>" : "Tidak Aktif"; })
                ->editColumn('updated_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s'); return $formatedDate; })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form input data
    public function create()
    {
        if (!UserInfoHelper::has_access("school_year", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("school_year", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $school_year = SchoolYear::find($decrypt->id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "school_year" => $school_year
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("school_year", "add")) return abort(404);
        $result = SchoolYear::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("school_year", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = SchoolYear::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("school_year", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = SchoolYear::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request) {
        if (!UserInfoHelper::has_access("school_year", "export")) return abort(404);
        $school_years = SchoolYear::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Nama'],
            ['text' => 'Semester'],
            ['text' => 'Aktif / Tidak'],
        ];
        foreach ($school_years as $sy) {
            $is_active = "";

            if ($sy->is_active == 1) {
                $is_active = "Aktif";
            } else {
                $is_active = "Tidak Aktif";
            }

            $result[] = [
                ['text' => $sy->id],
                ['text' => $sy->name],
                ['text' => $sy->semester],
                ['text' => $is_active],
            ];
        }
        return response()->json($result);
    }

    // public function import_excel(Request $request) {
    //     if (!UserInfoHelper::has_access("position", "import")) return abort(404);
    //     Excel::import(new PositionImport, request()->file('file-excel'));
    //     $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
    //     return response()->json($result['client_response'], $result['code']);
    // }

}
