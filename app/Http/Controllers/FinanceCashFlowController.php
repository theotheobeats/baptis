<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Imports\PositionImport;
use App\Models\CashAccount;
use App\Models\Due;
use App\Models\Employee;
use App\Models\FinanceAccount;
use App\Models\FinanceCashFlow;
use App\Models\FinanceCashFlowFile;
use App\Models\InvoiceDetailPayment;
use App\Models\InvoicePayment;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class FinanceCashFlowController extends Controller
{

    public static $information = [
        "title" => "Arus Kas",
        "route" => "/finance/cashflow",
        "view" => "pages.finance.cashflow."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan halaman index
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "view")) return abort(404);
        if ($request->ajax()) {
            $date_from = $request->date_from;
            $date_to = $request->date_to;

            $finance_cash_flows = new FinanceCashFlow();
            $finance_cash_flows = $finance_cash_flows->join("finance_accounts", "finance_accounts.id", "=", "finance_cash_flows.account_id");
            $finance_cash_flows = $finance_cash_flows->select(
                "finance_cash_flows.*", 
                "finance_accounts.code as account_code",
                "finance_accounts.name as account_name",
            );

            if ($date_from != null) {
                $finance_cash_flows = $finance_cash_flows->where('finance_cash_flows.transaction_date', '>=', $date_from);
            }
            
            if ($date_to != null) {
                $finance_cash_flows = $finance_cash_flows->where('finance_cash_flows.transaction_date', '<=', $date_to);
            }

            if ($request->cash_account_id != null) {
                $finance_cash_flows = $finance_cash_flows->where('finance_cash_flows.account_id', '=', $request->cash_account_id);
            }

            if ($request->note != null) {
                $finance_cash_flows = $finance_cash_flows->where('finance_cash_flows.note', 'like', '%' . $request->note . '%');
            }

            // $finance_cash_flows = $finance_cash_flows->orderBy("finance_cash_flows.created_at", "desc");
            return DataTables::of($finance_cash_flows)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $verify_action = 'verify_confirm("' . url(self::$information['route'] . '/verify') . '/' . $encrypted_id . '")';
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";

                    if ($row->source == FinanceCashFlow::SOURCE_BY_SYSTEM && $row->verified_at == null) {
                        $btn .= "<a class='btn btn-outline-success' onclick='$verify_action' title='Verifikasi Data'><i class='fa fa-check'></i></a>";
                    }

                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('debit', function ($data) {
                    return number_format($data->debit, 0, ',', '.');
                })
                ->editColumn('credit', function ($data) {
                    return number_format($data->credit, 0, ',', '.');
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
        if (!UserInfoHelper::has_access("cashflow", "add")) return abort(404);
        $finance_accounts =  FinanceAccount::select("id", "name")->get();
        return view(self::$information['view'] . 'add', [
            "information" => self::$information,
            "finance_accounts" => $finance_accounts
        ]);
    }

    // Menampilkan form input data
    public function create2()
    {
        if (!UserInfoHelper::has_access("cashflow", "add")) return abort(404);
        $finance_accounts =  FinanceAccount::select("id", "name")->get();
        return view(self::$information['view'] . 'add-2', [
            "information" => self::$information,
            "finance_accounts" => $finance_accounts
        ]);
    }


    // Menampilkan form edit data
    public function edit($id, Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $finance_cash_flow = FinanceCashFlow::find($decrypt->id);
        $finance_cash_flow_files = FinanceCashFlowFile::where("finance_cash_flow_id", $decrypt->id)->get();
        $finance_accounts =  FinanceAccount::select("id", "name")->get();

        return view(self::$information['view'] . 'edit', [
            "information" => self::$information,
            "finance_cash_flow" => $finance_cash_flow,
            "finance_cash_flow_files" => $finance_cash_flow_files,
            "finance_accounts" => $finance_accounts
        ]);
    }




    // ======================
    // Line Proses - START
    // ======================


    // Proses input data yang diinput user di view ke model
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "add")) return abort(404);
        $result = FinanceCashFlow::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    // Proses input data yang diinput user di view ke model
    public function store2(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "add")) return abort(404);
        $result = FinanceCashFlow::do_store2($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses update data dari form edit ke model
    public function update($id, Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "update")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceCashFlow::do_update($decrypt->id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }


    // Proses hapus data
    public function destroy($id)
    {
        if (!UserInfoHelper::has_access("cashflow", "delete")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceCashFlow::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    // Proses verifikasi data
    public function verify($id)
    {
        // if (!UserInfoHelper::has_access("cashflow", "")) return abort(404);
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceCashFlow::do_verify($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function export_excel(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "export")) return abort(404);
        $finance_cash_flows = FinanceCashFlow::get();
        $result = array();
        $result[] = [
            ['text' => 'ID'],
            ['text' => 'Akun Kas'],
            ['text' => 'Kode'],
            ['text' => 'Nomor Transaksi'],
            ['text' => 'Keterangan'],
            ['text' => 'Debit'],
            ['text' => 'Kredit'],
        ];
        foreach ($finance_cash_flows as $fcf) {
            $account_name = "";
            $account = FinanceAccount::find($fcf->account_id);
            if ($account) {
                $account_name = $account->name;
            }

            $result[] = [
                ['text' => $fcf->id],
                ['text' => $account_name],
                ['text' => $fcf->code],
                ['text' => $fcf->transaction_number],
                ['text' => $fcf->note],
                ['text' => $fcf->debit],
                ['text' => $fcf->credit],
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

    // BLOCK BUKA DAN TUTUP KAS
    public function open_cash_account(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow", "add")) return abort(404);
        return view('/pages/finance/cashflow/open-cash-account', []);
    }



    public function open_print($id, $type)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $cash_account = CashAccount::find($decrypt->id);
        $employee = Employee::find($cash_account->created_by);
        $type = $type;

        return view('/pages/finance/cashflow/open-cash-receipt', [
            "cash_account" => $cash_account,
            "employee" => $employee,
            "type" => $type
        ]);
    }

    public function close_print($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $cash_account = CashAccount::find($decrypt->id);
        $employee = Employee::find($cash_account->created_by);

        return view('/pages/finance/cashflow/close-cash-receipt', [
            "cash_account" => $cash_account,
            "employee" => $employee,
        ]);
    }

    public function do_open(Request $request)
    {
        $result = CashAccount::do_open($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function do_close(Request $request)
    {
        $result = CashAccount::do_close($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function delete_file_handover($id, Request $request)
    {
        $decrypt = CryptoHelper::decrypt($id);
        if (!$decrypt->success) return $decrypt->error_response;

        $result = FinanceCashFlowFile::do_delete($decrypt->id);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function sync_payment_to_cashflow(Request $request)
    {
        $invoice_payments = InvoicePayment::where("bank_id", "=", "2")->get();
        foreach ($invoice_payments as $invoice_payment) 
        {
            // Get cashflow
            $finance_cash_flows = FinanceCashFlow::where("code", "=", $invoice_payment->code)->get();

            // Get invoice detail payment
            $invoice_detail_payments = InvoiceDetailPayment::where("invoice_payment_id", "=", $invoice_payment->id)->get();

            // Cek jika jumlah cashflow dan detail payment sama
            if (count($finance_cash_flows) == count($invoice_detail_payments)) {
                // Replace nilai cashflow saja
                for ($i = 0; $i < count($finance_cash_flows); $i++) {
                    $_finance_cash_flow = FinanceCashFlow::find($finance_cash_flows[$i]->id);
                    $_finance_cash_flow->credit = $invoice_detail_payments[$i]->price;
                    $_finance_cash_flow->save();
                }
                echo "DONE - " . $invoice_payment->id . "<br>";

                // Input cashflow baru
            } else {
                echo "TIDAK SAMA - " . json_encode($invoice_payment->code) . "<br>";
            }
        }
    }
}
