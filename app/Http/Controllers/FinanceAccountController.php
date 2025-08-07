<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\CoaImport;
use App\Imports\PositionImport;
use App\Models\Due;
use App\Models\FinanceAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class FinanceAccountController extends Controller
{

    public static $information = [
        "title" => "Akun Kas",
        "route" => "/finance/coa",
        "view" => "pages.finance.coa."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("coa", "view")) return abort(404);
        if ($request->ajax()) {
            $finance_accounts = new FinanceAccount();
            $finance_accounts = $finance_accounts->select("finance_accounts.*");
            return DataTables::of($finance_accounts)
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
        if (!UserInfoHelper::has_access("coa", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("coa", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $finance_account = FinanceAccount::find($decrypt->id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "finance_account" => $finance_account
        ]);
    }

    public function search(Request $request)
    {
        $finance_accounts = [];

        $finance_accounts = new FinanceAccount();
        $finance_accounts = $finance_accounts->where("name", "like", "%" . trim($request->q) . "%");
        if ($request->has("display_for_cashier")) {
            $finance_accounts = $finance_accounts->where("display_for_cashier", "=", 1);
        }
        $finance_accounts = $finance_accounts->select("id", "name as text", "sub_detail",);
        $finance_accounts = $finance_accounts->limit(15);
        $finance_accounts = $finance_accounts->get();

        return response()->json($finance_accounts);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("coa", "add")) return abort(404);
        $result = FinanceAccount::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("coa", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceAccount::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("coa", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceAccount::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("coa", "export")) return abort(404);
        $finance_accounts = FinanceAccount::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Kode'],
            ['text' => 'Nama'],
            ['text' => 'Deskripsi'],
            ['text' => 'Sub Detail'],
            ['text' => 'Tampil Ke Kasir'],
        ];
        foreach ($finance_accounts as $fa) {
            $result[] = [
                ['text' => $fa->id],
                ['text' => $fa->code],
                ['text' => $fa->name],
                ['text' => $fa->description],
                ['text' => $fa->sub_detail],
                ['text' => $fa->display_for_cashier == 1 ? 'Ya' : 'Tidak'],
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request) {
        if (!UserInfoHelper::has_access("coa", "import")) return abort(404);
        Excel::import(new CoaImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }

}
