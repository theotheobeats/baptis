<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\DueImport;
use App\Imports\PositionImport;
use App\Models\Due;
use App\Models\FinanceAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class DueController extends Controller
{

    public static $information = [
        "title" => "Master Iuran",
        "route" => "/master/due",
        "view" => "pages.master.due."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("due", "view")) return abort(404);
        if ($request->ajax()) {
            $dues = new Due();
            $dues = $dues->select("dues.*");
            return DataTables::of($dues)
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
                ->editColumn('updated_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
                    return $formatedDate;
                })
                ->editColumn('price', function ($data) {
                    return 'Rp. ' . number_format($data->price, 0, ',', '.');
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
        if (!UserInfoHelper::has_access("due", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("due", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $due = Due::find($decrypt->id);
        $selected_finance_account = FinanceAccount::withTrashed()->find($due->finance_account_id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "due" => $due,
            "selected_finance_account" => $selected_finance_account
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("due", "add")) return abort(404);
        $result = Due::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("due", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Due::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("due", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Due::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("due", "export")) return abort(404);
        $dues = Due::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Nama'],
            ['text' => 'Harga'],
        ];
        foreach ($dues as $due) {
            $result[] = [
                ['text' => $due->id],
                ['text' => $due->name],
                ['text' => $due->price],
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("due", "import")) return abort(404);
        Excel::import(new DueImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }
}
