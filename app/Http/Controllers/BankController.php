<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\BankImport;
use App\Imports\DueImport;
use App\Imports\PositionImport;
use App\Models\Bank;
use App\Models\Due;
use App\Models\FinanceAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{

    public static $information = [
        "title" => "Master Bank",
        "route" => "/master/bank",
        "view" => "pages.master.bank."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "view")) return abort(404);
        if ($request->ajax()) {
            $banks = new Bank();
            $banks = $banks->join("finance_accounts", "finance_accounts.id", "=", "banks.finance_account_id");
            $banks = $banks->select("banks.*", "finance_accounts.name as finance_account_name");
            return DataTables::of($banks)
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
        if (!UserInfoHelper::has_access("bank", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $bank = Bank::find($decrypt->id);

        $finance_account = FinanceAccount::find($bank->finance_account_id);

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "bank" => $bank,
            "finance_account" => $finance_account
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "add")) return abort(404);
        $result = Bank::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Bank::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("bank", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = Bank::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "export")) return abort(404);
        $banks = Bank::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Nama'],
            ['text' => 'Nama PIC'],
            ['text' => 'Telfon PIC'],
        ];
        foreach ($banks as $bank) {
            $result[] = [
                ['text' => $bank->id],
                ['text' => $bank->name],
                ['text' => $bank->pic_name],
                ['text' => $bank->pic_phone],
            ];
        }
        return response()->json($result);
    }

    public function import_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("bank", "import")) return abort(404);
        Excel::import(new BankImport, request()->file('file-excel'));
        $result = ResponseHelper::response_success('Berhasil', 'Data telah diimport');
        return response()->json($result['client_response'], $result['code']);
    }
}
