<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Models\Bank;
use App\Models\CashierTransaction;
use App\Models\CashierTransactionFile;
use App\Models\FinanceAccount;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CashierTransactionController extends Controller
{

    public static $information = [
        "title" => "Penjualan Kasir",
        "route" => "/transaction/cashier",
        "view" => "pages.transactions.cashier."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    // public function index(Request $request)
    // {
    //     if (!UserInfoHelper::has_access("cashier", "view")) return abort(404);
    //     if ($request->ajax()) {
    //         $cashier_transactions = new CashierTransaction();
    //         $cashier_transactions = $cashier_transactions->join("banks", "banks.id", "=", "cashier_transactions.bank_id");
    //         $cashier_transactions = $cashier_transactions->join("finance_accounts", "finance_accounts.id", "=", "cashier_transactions.account_id");
    //         $cashier_transactions = $cashier_transactions->select("cashier_transactions.*", "finance_accounts.name as account_name", "banks.name as bank_name");
    //         $cashier_transactions = $cashier_transactions->orderBy("cashier_transactions.created_at", "desc");
    //         return DataTables::of($cashier_transactions)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 $encrypted_id = Crypt::encrypt($row->id);
    //                 $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
    //                 $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
    //                 $btn = "<div class='btn-group m-0'>";
    //                 $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
    //                 $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
    //                 $btn .= "</div>";
    //                 return $btn;
    //             })
    //             ->editColumn('transaction_date', function ($data) {
    //                 return Carbon::createFromFormat('Y-m-d', $data->transaction_date)->translatedFormat('d-m-Y');
    //             })
    //             ->editColumn('amount', function ($data) {
    //                 return number_format($data->amount, 0, ',', '.');
    //             })
    //             ->editColumn('updated_at', function ($data) {
    //                 $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->translatedFormat('d F Y - H:i:s');
    //                 return $formatedDate;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }

    //     return view(self::$information['view'] . 'index', [
    //         "information" => self::$information
    //     ]);
    // }


    // Menampilkan form input data
    // public function create()
    // {
    //     if (!UserInfoHelper::has_access("cashier", "add")) return abort(404);
    //     return view(self::$information['view'] . 'add', [
    //         "information" => self::$information,
    //     ]);
    // }


    // Menampilkan form edit data
    // public function edit($id, Request $request)
    // {
    //     if (!UserInfoHelper::has_access("cashier", "update")) return abort(404);
    //     $decrypt = CryptoHelper::decrypt($id);
    //     if (!$decrypt->success) return $decrypt->error_response;

    //     $cashier_transaction = CashierTransaction::find($decrypt->id);
    //     $finance_accounts =  FinanceAccount::select("id", "name")->get();
    //     $banks = Bank::select("id", "name")->get();

    //     return view(self::$information['view'] . 'edit', [
    //         "information" => self::$information,
    //         "cashier_transaction" => $cashier_transaction,
    //         "finance_accounts" => $finance_accounts,
    //         "banks" => $banks,
    //     ]);
    // }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    // public function store(Request $request)
    // {
    //     if (!UserInfoHelper::has_access("cashier", "add")) return abort(404);
    //     $result = CashierTransaction::do_store($request);
    //     return response()->json($result["client_response"], $result["code"]);
    // }


    // Proses update data dari form edit ke model
    // public function update($id, Request $request)
    // {
    //     if (!UserInfoHelper::has_access("cashier", "update")) return abort(404);
    //     $decrypt = CryptoHelper::decrypt($id);
    //     if (!$decrypt->success) return $decrypt->error_response;

    //     $result = CashierTransaction::do_update($decrypt->id, $request);
    //     return response()->json($result["client_response"], $result["code"]);
    // }


    // Proses hapus data
    // public function destroy($id)
    // {
    //     if (!UserInfoHelper::has_access("cashier", "delete")) return abort(404);
    //     $decrypt = CryptoHelper::decrypt($id);
    //     if (!$decrypt->success) return $decrypt->error_response;

    //     $result = CashierTransaction::do_delete($decrypt->id);
    //     return response()->json($result["client_response"], $result["code"]);
    // }
}
