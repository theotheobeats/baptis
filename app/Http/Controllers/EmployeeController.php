<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\EmployeeImport;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static $information = [
        "title" => "Master Karyawan",
        "route" => "/master/employee",
        "view" => "pages.master.employee."
    ];


    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "view")) return abort(404);
        if ($request->ajax()) {
            $employees = new Employee();
            $employees = $employees->leftJoin("positions", "positions.id", "=", "employees.position_id");
            $employees = $employees->select("employees.*", "positions.name as position_name");
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('updated_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->rawColumns(['action'])
                ->make(true);
        };

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }

    // Menampilkan form input data
    public function create()
    {
        if (!UserInfoHelper::has_access("employee", "add")) return abort(404);
        $positions = Position::select("id", "name")->get();
        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
            "positions" => $positions
        ]);
    }

    //form untuk edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $employee = Employee::find($decrypt->id);
        $positions = Position::select("id", "name")->get();

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "employee" => $employee,
            "positions" => $positions
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput empl$employee di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "add")) return abort(404);
        $result = Employee::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Employee::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("employee", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Employee::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }


    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "export")) return abort(404);
        $employee = Employee::get();
        $result = array();
        $result[] = [
            ["text" => "ID*"],
            ["text" => "Nama"],
            ["text" => "Alamat"],
            ["text" => "Nomor HP"],
            ["text" => "Jabatan"],
        ];

        foreach ($employee as $employee) {
            $position = Employee::find($employee->position_id);
            $position_name = null;
            if ($employee != null) {
                $position_name = $position->name;
            }

            $result[] = [
                ["text" => $employee->id],
                ["text" => $employee->name],
                ["text" => $employee->address],
                ["text" => $employee->phone_number],
                ["text" => $position_name],
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("employee", "import")) return abort(404);
        Excel::import(new EmployeeImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success("Berhasil", "Data telah diimport");
        return response()->json($result["client_response"], $result["code"]);
    }

    public function access(Request $request)
    {
        if ($request->ajax()) {
            $accessibility_id = $request->accessibility_id;
            // $accessibility = Accessibility::find($accessibility_id);
            // if ($accessibility != null) return response($accessibility->access);
        }
    }
}
