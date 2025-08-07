<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Models\Bank;
use App\Models\CashierPayment;
use App\Models\CashierPaymentFile;
use App\Models\FinanceAccount;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class CashierPaymentController extends Controller
{

    public static $information = [
        "title" => "Pembayaran",
        "route" => "/transaction/cashier-payment",
        "view" => "pages.transactions.cashier-payment."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_payment", "view")) return abort(404);
        if ($request->ajax()) {
            $cashier_payments = new CashierPayment();
            $cashier_payments = $cashier_payments->leftJoin("students", "students.id", "=", "cashier_payments.student_id");
            $cashier_payments = $cashier_payments->select("cashier_payments.*", "students.name as student_name");
            $cashier_payments = $cashier_payments->orderBy("cashier_payments.created_at", "desc");
            return DataTables::of($cashier_payments)
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
                ->editColumn('date', function ($data) {
                    return Carbon::createFromFormat('Y-m-d', $data->date)->translatedFormat('d-m-Y');
                })
                ->editColumn('amount', function ($data) {
                    return number_format($data->amount, 0, ',', '.');
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
        if (!UserInfoHelper::has_access("cashier_payment", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_payment", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $cashier_payment = CashierPayment::find($decrypt->id);
        $finance_accounts =  FinanceAccount::select("id", "name")->get();
        $banks = Bank::select("id", "name")->get();

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "cashier_payment" => $cashier_payment,
            "finance_accounts" => $finance_accounts,
            "banks" => $banks,
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_payment", "add")) return abort(404);
        $result = CashierPayment::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_payment", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = CashierPayment::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("cashier_payment", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = CashierPayment::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }
}
