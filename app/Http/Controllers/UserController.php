<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\UserImport;
use App\Models\Accessibility;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static $information = [
        "title" => "Master Pengguna",
        "route" => "/master/user",
        "view" => "pages.master.user."
    ];


    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("user", "view")) return abort(404);
        if ($request->ajax()) {
            $users = new User;
            $users = $users->join('employees', 'employees.id', '=', 'users.employee_id');
            $users = $users->select("users.*", 'employees.name as employee_name');
            return DataTables::of($users)
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
        if (!UserInfoHelper::has_access("user", "add")) return abort(404);
        $employees = Employee::select("id", "name")->get();
        $accessibilities = Accessibility::select("id", "name", "access")->get();
        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
            "employees" => $employees,
            "accessibilities" => $accessibilities
        ]);
    }

    //form untuk edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("user", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $user = User::find($decrypt->id);
        $employees = Employee::select("id", "name")->get();
        $accessibilities = Accessibility::select("id", "name", "access")->get();
        $data = json_decode($user->access);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "user" => $user,
            "employees" => $employees,
            "accessibilities" => $accessibilities,
            "data" => $data
        ]);
    }

    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("user", "add")) return abort(404);
        $result = User::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("user", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = User::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("user", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = User::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }



    // Method lain - lain
    public function toggle_dark_theme()
    {
        $user_id = UserInfoHelper::user_id();
        $user = User::find($user_id);
        if ($user != null) {
            $user->config_dark_theme = $user->config_dark_theme == 1 ? 0 : 1;
            $user->save();
        }
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("user", "export")) return abort(404);
        $users = User::get();
        $result = array();
        $result[] = [
            ["text" => "ID*"],
            ["text" => "Email"],
            ["text" => "Username"],
            ["text" => "Password"],
            ["text" => "Pemilik Akun"]
        ];

        foreach ($users as $user) {
            $employee = Employee::find($user->employee_id);
            $employee_name = null;
            if ($employee != null) {
                $employee_name = $employee->name;
            }

            $result[] = [
                ["text" => $user->id],
                ["text" => $user->email],
                ["text" => $user->username],
                ["text" => "secret"],
                ["text" => $employee_name]
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("user", "import")) return abort(404);
        Excel::import(new UserImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success("Berhasil", "Data telah diimport");
        return response()->json($result["client_response"], $result["code"]);
    }

    public function access(Request $request)
    {
        if ($request->ajax()) {
            $accessibility_id = $request->accessibility_id;
            $accessibility = Accessibility::find($accessibility_id);
            if ($accessibility != null) return response($accessibility->access);
        }
    }
}
