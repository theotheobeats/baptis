<?php

namespace App\Http\Controllers;

use App\Helpers\ExportHelper;
use Carbon\Carbon;
use App\Helpers\UserInfoHelper;
use App\Models\Bank;
use App\Models\CashierTransaction;
use App\Models\Due;
use App\Models\Employee;
use App\Models\FinanceAccount;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\FinanceCashFlow;
use App\Models\InvoiceDetailPayment;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function payment(Request $request)
    {
        if (!UserInfoHelper::has_access("payment_report", "view")) return abort(404);
        return view('pages.reports.payment.index', []);
    }

    public function balance_sheet(Request $request)
    {
        if (!UserInfoHelper::has_access("balance_sheet_report", "view")) return abort(404);
        return view('pages.reports.balance-sheet.index', []);
    }

    public function cashflow(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow_report", "view")) return abort(404);
        return view('pages.reports.cashflow.index', []);
    }

    public function profit(Request $request)
    {
        if (!UserInfoHelper::has_access("profit_report", "view")) return abort(404);
        return view('pages.reports.profit.index', []);
    }

    public function cashier(Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_report", "view")) return abort(404);
        return view('pages.reports.cashier.index', []);
    }

    public function student_not_paid(Request $request)
    {
        if (!UserInfoHelper::has_access("student_not_paid_report", "view")) return abort(404);
        return view('pages.reports.student-not-paid.index', []);
    }

    public function student_paid_detail(Request $request)
    {
        if (!UserInfoHelper::has_access("student_paid_detail_report", "view")) return abort(404);
        return view('pages.reports.student-paid-detail.index', []);
    }

    public function student_over_paid(Request $request)
    {
        if (!UserInfoHelper::has_access("student_over_paid_report", "view")) return abort(404);
        return view('pages.reports.student-over-paid.index', []);
    }











    // PRINT BLOCK
    public function payment_print(Request $request)
    {
        ini_set('memory_limit', '256M');
        if (!UserInfoHelper::has_access("payment_report", "export")) return abort(404);
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $created_by = $request->employee_id;

        if ($created_by != null) {
            $employee = Employee::find($created_by);
        }

        $date_from = $date_from . " 00:00:00";
        $date_to = $date_to . " 23:59:59";

        $invoice_payments = new InvoicePayment();
        $invoice_payments = $invoice_payments->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id');
        $invoice_payments = $invoice_payments->join('students', 'students.id', '=', 'invoices.student_id');
        $invoice_payments = $invoice_payments->whereBetween('invoice_payments.created_at', [$date_from, $date_to]);
        if ($created_by != null) {
            $invoice_payments = $invoice_payments->where('invoice_payments.created_by', "=", $created_by);
        }
        $invoice_payments = $invoice_payments->select("invoice_payments.*", "students.name as student_name", "students.nis as student_nis");
        $invoice_payments = $invoice_payments->get();

        $dues = Due::get();

        $invoice_detail_payments = new InvoiceDetailPayment();
        $invoice_detail_payments = $invoice_detail_payments->join('invoice_details', 'invoice_details.id', '=', 'invoice_detail_payments.invoice_detail_id');
        $invoice_detail_payments = $invoice_detail_payments->join('invoice_payments', 'invoice_payments.id', '=', 'invoice_detail_payments.invoice_payment_id');
        $invoice_detail_payments = $invoice_detail_payments->select("invoice_detail_payments.*", "invoice_details.due_id", "invoice_payments.id");
        $invoice_detail_payments = $invoice_detail_payments->whereBetween('invoice_payments.created_at', [$date_from, $date_to]);
        $invoice_detail_payments = $invoice_detail_payments->get();

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "dues" => $dues,
            "invoice_payments" => $invoice_payments,
            "invoice_detail_payments" => $invoice_detail_payments,
            "employee" => $employee ?? null
        ];

        if ($request->type) {
            $fileName = "Laporan Pembayaran Iuran";
            $view = 'pages.reports.payment.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.payment.print', $data);
    }

    public function balance_sheet_print(Request $request)
    {
        if (!UserInfoHelper::has_access("balance_sheet_report", "export")) return abort(404);
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $created_by = $request->employee_id;

        if ($created_by != null) {
            $employee = Employee::find($created_by);
        }

        $balance_sheet_code_list = [
            "1000",
            "1100",
            "1700",
            "1710",
            "2000",
            "2100",
            "2200",
            "3000"
        ];

        // Get kas dan bank (1000)
        // Get piutang (1100)

        // Get aktiva tetap (1700)
        // Get akumulasi penyusutan aktiva tetap (1710)

        // Get hutang (2000)
        // Get hutang jangka pendek (2100)
        // Get hutang pembelian belum ditagih (2200)

        // Get ekuitas (3000)

        // 1. Get kas dan bank (1000)
        $report_1_accounts = FinanceAccount::where("code", "like", "100%")->where("hide_coa", "=", 0)->get();
        $report_1_account_list = FinanceAccount::where("code", "like", "100%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_1 = new FinanceCashFlow;
        $report_1 = $report_1->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_1 = $report_1->whereIn("finance_cash_flows.account_id", $report_1_account_list);
        if ($created_by != null) {
            $report_1 = $report_1->where('finance_cash_flows.created_by', $created_by);
        }
        $report_1 = $report_1->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_1 = $report_1->groupBy("finance_cash_flows.account_id");
        $report_1 = $report_1->get();

        // 2. Get piutang (1100)
        $report_2_accounts = FinanceAccount::where("code", "like", "110%")->where("hide_coa", "=", 0)->get();
        $report_2_account_list = FinanceAccount::where("code", "like", "110%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_2 = new FinanceCashFlow;
        $report_2 = $report_2->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_2 = $report_2->whereIn("finance_cash_flows.account_id", $report_2_account_list);
        if ($created_by != null) {
            $report_2 = $report_2->where('finance_cash_flows.created_by', $created_by);
        }
        $report_2 = $report_2->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_2 = $report_2->groupBy("finance_cash_flows.account_id");
        $report_2 = $report_2->get();

        // 3. Get aktiva tetap (1700)
        $report_3_accounts = FinanceAccount::where("code", "like", "1700%")->where("hide_coa", "=", 0)->get();
        $report_3_account_list = FinanceAccount::where("code", "like", "1700%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_3 = new FinanceCashFlow;
        $report_3 = $report_3->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_3 = $report_3->whereIn("finance_cash_flows.account_id", $report_3_account_list);
        if ($created_by != null) {
            $report_3 = $report_3->where('finance_cash_flows.created_by', $created_by);
        }
        $report_3 = $report_3->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_3 = $report_3->groupBy("finance_cash_flows.account_id");
        $report_3 = $report_3->get();

        // 4. Get akumulasi penyusutan aktiva tetap (1710)
        $report_4_accounts = FinanceAccount::where("code", "like", "1710%")->where("hide_coa", "=", 0)->get();
        $report_4_account_list = FinanceAccount::where("code", "like", "1710%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_4 = new FinanceCashFlow;
        $report_4 = $report_4->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_4 = $report_4->whereIn("finance_cash_flows.account_id", $report_4_account_list);
        if ($created_by != null) {
            $report_4 = $report_4->where('finance_cash_flows.created_by', $created_by);
        }
        $report_4 = $report_4->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_4 = $report_4->groupBy("finance_cash_flows.account_id");
        $report_4 = $report_4->get();

        // 5. Get hutang (2000)
        $report_5_accounts = FinanceAccount::where("code", "like", "2000%")->where("hide_coa", "=", 0)->get();
        $report_5_account_list = FinanceAccount::where("code", "like", "2000%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_5 = new FinanceCashFlow;
        $report_5 = $report_5->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_5 = $report_5->whereIn("finance_cash_flows.account_id", $report_5_account_list);
        if ($created_by != null) {
            $report_5 = $report_5->where('finance_cash_flows.created_by', $created_by);
        }
        $report_5 = $report_5->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_5 = $report_5->groupBy("finance_cash_flows.account_id");
        $report_5 = $report_5->get();

        // 6. Get hutang jangka pendek (2100)
        $report_6_accounts = FinanceAccount::where("code", "like", "2100%")->where("hide_coa", "=", 0)->get();
        $report_6_account_list = FinanceAccount::where("code", "like", "2100%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_6 = new FinanceCashFlow;
        $report_6 = $report_6->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_6 = $report_6->whereIn("finance_cash_flows.account_id", $report_6_account_list);
        if ($created_by != null) {
            $report_6 = $report_6->where('finance_cash_flows.created_by', $created_by);
        }
        $report_6 = $report_6->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_6 = $report_6->groupBy("finance_cash_flows.account_id");
        $report_6 = $report_6->get();

        // 7. Get hutang pembelian belum ditagih (2200)
        $report_7_accounts = FinanceAccount::where("code", "like", "2200%")->where("hide_coa", "=", 0)->get();
        $report_7_account_list = FinanceAccount::where("code", "like", "2200%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_7 = new FinanceCashFlow;
        $report_7 = $report_7->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_7 = $report_7->whereIn("finance_cash_flows.account_id", $report_7_account_list);
        if ($created_by != null) {
            $report_7 = $report_7->where('finance_cash_flows.created_by', $created_by);
        }
        $report_7 = $report_7->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_7 = $report_7->groupBy("finance_cash_flows.account_id");
        $report_7 = $report_7->get();

        // 8. Get ekuitas (3000)
        $report_8_accounts = FinanceAccount::where("code", "like", "3000%")->where("hide_coa", "=", 0)->get();
        $report_8_account_list = FinanceAccount::where("code", "like", "3000%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_8 = new FinanceCashFlow;
        $report_8 = $report_8->whereBetween("finance_cash_flows.created_at", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_8 = $report_8->whereIn("finance_cash_flows.account_id", $report_8_account_list);
        if ($created_by != null) {
            $report_8 = $report_8->where('finance_cash_flows.created_by', $created_by);
        }
        $report_8 = $report_8->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_8 = $report_8->groupBy("finance_cash_flows.account_id");
        $report_8 = $report_8->get();

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "employee" => $employee ?? null,
            "report_1_accounts" => $report_1_accounts,
            "report_2_accounts" => $report_2_accounts,
            "report_3_accounts" => $report_3_accounts,
            "report_4_accounts" => $report_4_accounts,
            "report_5_accounts" => $report_5_accounts,
            "report_6_accounts" => $report_6_accounts,
            "report_7_accounts" => $report_7_accounts,
            "report_8_accounts" => $report_8_accounts,
            "report_1" => $report_1,
            "report_2" => $report_2,
            "report_3" => $report_3,
            "report_4" => $report_4,
            "report_5" => $report_5,
            "report_6" => $report_6,
            "report_7" => $report_7,
            "report_8" => $report_8,
        ];

        if ($request->type) {
            $fileName = "Laporan Neraca";
            $view = 'pages.reports.balance-sheet.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.balance-sheet.print', $data);

    }

    public function cashflow_print(Request $request)
    {
        if (!UserInfoHelper::has_access("cashflow_report", "export")) return abort(404);
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $created_by = $request->employee_id;

        if ($created_by != null) {
            $employee = Employee::find($created_by);
        }

        $cashflows = new FinanceCashFlow();
        $cashflows = $cashflows->leftJoin('finance_accounts', 'finance_accounts.id', '=', 'finance_cash_flows.account_id')
            ->where('finance_accounts.hide_coa', '=', 0)
            ->whereBetween('finance_cash_flows.transaction_date', [$date_from . " 00:00:00", $date_to . " 23:59:59"])
            ->orderBy('finance_cash_flows.transaction_date')
            ->select("finance_cash_flows.*", "finance_accounts.name as account_name");

        if ($request->account_id != "") {
            $cashflows = $cashflows->whereIn('finance_cash_flows.account_id', $request->account_id);
        }

        if ($created_by != null) {
            $cashflows = $cashflows->where('finance_cash_flows.created_by', $created_by);
        }

        $cashflows = $cashflows->get();

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "cashflows" => $cashflows,
            "employee" => $employee ?? null
        ];

        if ($request->type) {
            $fileName = "Laporan Arus Kas";
            $view = 'pages.reports.cashflow.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.cashflow.print', $data);
    }

    public function profit_print(Request $request)
    {
        if (!UserInfoHelper::has_access("profit_report", "export")) return abort(404);
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $created_by = $request->employee_id;

        if ($created_by != null) {
            $employee = Employee::find($created_by);
        }

        $profit_lost_code_list = [
            "4000",
            "6000",
            "6100",
            "6200",
            "6300",
            "7100",
            "7200",
        ];

        // Get data pendapatan (4000)
        // Get data biaya langsung (6100)
        // Get data biaya tak langsung (6200)
        // Get biaya admnistrasi (6300)
        // Get pendapatan operasional (6300)
        // Get pendapatan lain-lain (7100)
        // Get pengeluaran lain-lain (7200)

        // 1. Get data pendapatan (4000)
        $report_1_accounts = FinanceAccount::where("code", "like", "4000%")->where("hide_coa", "=", 0)->get();
        $report_1_account_list = FinanceAccount::where("code", "like", "4000%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_1 = new FinanceCashFlow;
        $report_1 = $report_1->join("finance_accounts", "finance_accounts.id", "=", "finance_cash_flows.account_id");
        $report_1 = $report_1->where("finance_cash_flows.transaction_date", ">=", $date_from . " 00:00:00");
        $report_1 = $report_1->where("finance_cash_flows.transaction_date", "<=", $date_to . " 23:59:59");
        $report_1 = $report_1->whereIn("finance_cash_flows.account_id", $report_1_account_list);
        if ($created_by != null) {
            $report_1 = $report_1->where('finance_cash_flows.created_by', $created_by);
        }
        // $report_1 = $report_1->whereNull("finance_cash_flows.deleted_at");
        // $report_1 = $report_1->whereNull("finance_accounts.deleted_at");
        $report_1 = $report_1->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_1 = $report_1->groupBy("finance_cash_flows.account_id");
        $report_1 = $report_1->get();


        // 2. Get data biaya langsung (6100)
        $report_2_accounts = FinanceAccount::where("code", "like", "6100%")->where("hide_coa", "=", 0)->get();
        $report_2_account_list = FinanceAccount::where("code", "like", "6100%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_2 = new FinanceCashFlow;
        $report_2 = $report_2->whereBetween("finance_cash_flows.transaction_date", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_2 = $report_2->whereIn("finance_cash_flows.account_id", $report_2_account_list);
        if ($created_by != null) {
            $report_2 = $report_2->where('finance_cash_flows.created_by', $created_by);
        }
        $report_2 = $report_2->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_2 = $report_2->groupBy("finance_cash_flows.account_id");
        $report_2 = $report_2->get();


        // 3. Get data biaya tak langsung (6200)
        $report_3_accounts = FinanceAccount::where("code", "like", "6200%")->where("hide_coa", "=", 0)->get();
        $report_3_account_list = FinanceAccount::where("code", "like", "6200%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_3 = new FinanceCashFlow;
        $report_3 = $report_3->whereBetween("finance_cash_flows.transaction_date", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_3 = $report_3->whereIn("finance_cash_flows.account_id", $report_3_account_list);
        if ($created_by != null) {
            $report_3 = $report_3->where('finance_cash_flows.created_by', $created_by);
        }
        $report_3 = $report_3->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_3 = $report_3->groupBy("finance_cash_flows.account_id");
        $report_3 = $report_3->get();


        // 4. Get data biaya admnistrasi (6300)
        $report_4_accounts = FinanceAccount::where("code", "like", "6300%")->where("hide_coa", "=", 0)->get();
        $report_4_account_list = FinanceAccount::where("code", "like", "6300%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_4 = new FinanceCashFlow;
        $report_4 = $report_4->whereBetween("finance_cash_flows.transaction_date", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_4 = $report_4->whereIn("finance_cash_flows.account_id", $report_4_account_list);
        if ($created_by != null) {
            $report_4 = $report_4->where('finance_cash_flows.created_by', $created_by);
        }
        $report_4 = $report_4->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_4 = $report_4->groupBy("finance_cash_flows.account_id");
        $report_4 = $report_4->get();


        // 5. Get data pendapatan operasional (7100)
        $report_5_accounts = FinanceAccount::where("code", "like", "7100%")->where("hide_coa", "=", 0)->get();
        $report_5_account_list = FinanceAccount::where("code", "like", "7100%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_5 = new FinanceCashFlow;
        $report_5 = $report_5->whereBetween("finance_cash_flows.transaction_date", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_5 = $report_5->whereIn("finance_cash_flows.account_id", $report_5_account_list);
        if ($created_by != null) {
            $report_5 = $report_5->where('finance_cash_flows.created_by', $created_by);
        }
        $report_5 = $report_5->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_5 = $report_5->groupBy("finance_cash_flows.account_id");
        $report_5 = $report_5->get();

        // 6. Get data pengeluaran lainnya (7200)
        $report_6_accounts = FinanceAccount::where("code", "like", "7200%")->where("hide_coa", "=", 0)->get();
        $report_6_account_list = FinanceAccount::where("code", "like", "7200%")->where("hide_coa", "=", 0)->pluck('id')->toArray();
        $report_6 = new FinanceCashFlow;
        $report_6 = $report_6->whereBetween("finance_cash_flows.transaction_date", [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $report_6 = $report_6->whereIn("finance_cash_flows.account_id", $report_6_account_list);
        if ($created_by != null) {
            $report_6 = $report_6->where('finance_cash_flows.created_by', $created_by);
        }
        $report_6 = $report_6->select(
            DB::raw("SUM(finance_cash_flows.debit) as debit_total"),
            DB::raw("SUM(finance_cash_flows.credit) as credit_total"),
            "finance_cash_flows.account_id",
        );
        $report_6 = $report_6->groupBy("finance_cash_flows.account_id");
        $report_6 = $report_6->get();

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "employee" => $employee ?? null,
            "report_1_accounts" => $report_1_accounts,
            "report_2_accounts" => $report_2_accounts,
            "report_3_accounts" => $report_3_accounts,
            "report_4_accounts" => $report_4_accounts,
            "report_5_accounts" => $report_5_accounts,
            "report_6_accounts" => $report_6_accounts,
            "report_1" => $report_1,
            "report_2" => $report_2,
            "report_3" => $report_3,
            "report_4" => $report_4,
            "report_5" => $report_5,
            "report_6" => $report_6,
        ];

        if ($request->type) {
            $fileName = "Laporan Laba Rugi";
            $view = 'pages.reports.profit.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.profit.print', $data);

    }

    public function cashier_print(Request $request)
    {
        if (!UserInfoHelper::has_access("cashier_report", "export")) return abort(404);
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $cashier_credits = new FinanceCashFlow();
        $cashier_credits = $cashier_credits->leftJoin('finance_accounts', 'finance_accounts.id', '=', 'finance_cash_flows.account_id');
        $cashier_credits = $cashier_credits->where('finance_cash_flows.code', 'like', 'CT%');
        $cashier_credits = $cashier_credits->where('finance_cash_flows.credit', '>', 0);
        $cashier_credits = $cashier_credits->where('transaction_date', '>=', $date_from . " 00:00:00");
        $cashier_credits = $cashier_credits->where('transaction_date', '<=', $date_to . " 23:59:59");
        $cashier_credits = $cashier_credits->orderBy('finance_cash_flows.transaction_date');
        $cashier_credits = $cashier_credits->select("finance_cash_flows.*", "finance_accounts.name as account_name");
        $cashier_credits = $cashier_credits->get();

        $cashier_debits = new FinanceCashFlow();
        $cashier_debits = $cashier_debits->leftJoin('finance_accounts', 'finance_accounts.id', '=', 'finance_cash_flows.account_id');
        $cashier_debits = $cashier_debits->where('finance_cash_flows.code', 'like', 'CT%');
        $cashier_debits = $cashier_debits->where('finance_cash_flows.debit', '>', 0);
        $cashier_debits = $cashier_debits->where('transaction_date', '>=', $date_from . " 00:00:00");
        $cashier_debits = $cashier_debits->where('transaction_date', '<=', $date_to . " 23:59:59");
        $cashier_debits = $cashier_debits->orderBy('finance_cash_flows.transaction_date');
        $cashier_debits = $cashier_debits->select("finance_cash_flows.*", "finance_accounts.name as account_name");
        $cashier_debits = $cashier_debits->get();

        $dates = array_unique(array_merge($cashier_credits->pluck('transaction_date')->toArray(), $cashier_debits->pluck('transaction_date')->toArray()));

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "cashier_credits" => $cashier_credits,
            "cashier_debits" => $cashier_debits,
            "dates" => $dates
        ];

        if ($request->type) {
            $fileName = "Laporan Harian Kasir";
            $view = 'pages.reports.cashier.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.cashier.print', $data);
    }

    public function student_not_paid_print(Request $request)
    {
        if (!UserInfoHelper::has_access("student_not_paid_report", "export")) return abort(404);
        if ($request->group_by_student) {
            $invoices = new Invoice();
            $invoices = $invoices->leftJoin('students', 'students.id', '=', 'invoices.student_id');
            $invoices = $invoices->whereRaw('invoices.payed_amount < invoices.price');
            $invoices = $invoices->where('invoices.payment_due_date', '<', now());
            $invoices = $invoices->groupBy('invoices.student_id', 'students.name', 'students.nis');
            $invoices = $invoices->select(
                "invoices.student_id",
                "students.name as student_name",
                "students.nis as student_nis",
                DB::raw("SUM(invoices.price) - SUM(invoices.payed_amount) as not_paid_amount")
            );
            $invoices = $invoices->get();

            $data = [
                "invoices" => $invoices
            ];

            if ($request->type) {
                $fileName = "Laporan Siswa Belum Bayar";
                $view = 'pages.reports.student-not-paid.print-by-student';

                return ExportHelper::export(
                    $request->type,
                    $fileName,
                    $data,
                    $view,
                    $request,
                    [
                        'size' => 'legal',
                        'orientation' => 'landscape',
                    ],
                    $filters = []
                );
            }

            return view('pages.reports.student-not-paid.print-by-student', $data);
        }

        $invoice_details = new InvoiceDetail();
        $invoice_details = $invoice_details->leftJoin('invoices', 'invoices.id', '=', 'invoice_details.invoice_id');
        $invoice_details = $invoice_details->leftJoin('dues', 'dues.id', '=', 'invoice_details.due_id');
        $invoice_details = $invoice_details->leftJoin('classrooms', 'classrooms.id', '=', 'invoice_details.classroom_id');
        $invoice_details = $invoice_details->leftJoin('students', 'students.id', '=', 'invoices.student_id')
            // ->whereIn('invoices.status', ['pending', 'part_payment'])
            ->whereRaw('invoice_details.price - invoice_details.payed_amount > 0')
            ->where('invoices.payment_due_date', '<', now())
            ->select(
                "invoice_details.*",
                "students.name as student_name",
                "students.nis as student_nis",
                "classrooms.name as class_name",
                "dues.name as due_name"
            );
        $invoice_details = $invoice_details->get();

        $data =  [
            "invoices" => $invoice_details
        ];

        if ($request->type) {
            $fileName = "Laporan Siswa Belum Bayar";
            $view = 'pages.reports.student-not-paid.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.student-not-paid.print', $data);
    }

    public function student_paid_detail_print(Request $request)
    {
        if (!UserInfoHelper::has_access("student_paid_detail_report", "export")) return abort(404);
        $date_from = $request->date_from . " 00:00:00";
        $date_to = $request->date_to . " 23:59:59";

        $bank_id = $request->bank_id;
        $due_id = $request->due_id;
        $classroom_id = $request->classroom_id;

        $invoice_payments = new InvoicePayment();
        $invoice_payments = $invoice_payments->join('students', 'students.id', '=', 'invoice_payments.student_id');
        $invoice_payments = $invoice_payments->join('invoice_detail_payments', 'invoice_detail_payments.invoice_payment_id', '=', 'invoice_payments.id');
        $invoice_payments = $invoice_payments->join('invoice_details', 'invoice_details.id', '=', 'invoice_detail_payments.invoice_detail_id');
        $invoice_payments = $invoice_payments->join('dues', 'dues.id', '=', 'invoice_details.due_id');
        $invoice_payments = $invoice_payments->leftJoin('banks', 'banks.id', '=', 'invoice_payments.bank_id');
        $invoice_payments = $invoice_payments->where('invoice_payments.date', '>=', $date_from);
        $invoice_payments = $invoice_payments->where('invoice_payments.date', '<=', $date_to);

        if ($bank_id != null) {
            $invoice_payments = $invoice_payments->where('invoice_payments.bank_id', "=", $bank_id);
        }

        if ($classroom_id != null) {
            $invoice_payments = $invoice_payments->where('students.backtrack_current_classroom_id', "=", $classroom_id);
        }

        if ($due_id != null) {
            // join invoice
            $invoice_payments = $invoice_payments->where('invoice_details.due_id', "=", $due_id);
            // $invoice_payments = $invoice_payments->join('invoice_details', 'invoice_details.invoice_id', '=', 'invoice_payments.invoice_id');
            // $invoice_payments = $invoice_payments->where('invoice_details.due_id', "=", $due_id);
        }

        $invoice_payments = $invoice_payments->select(
            "invoice_payments.id",
            "invoice_detail_payments.id as invoice_detail_payment_id",
            "invoice_detail_payments.price",
            "invoice_payments.created_at",
            "invoice_payments.date",
            "students.id as student_id",
            "students.name as student_name",
            "students.nis as student_nis",
            "dues.name as due_name",
            "banks.name as bank_name"
        );
        $invoice_payments = $invoice_payments->get();

        $list_invoice_payment_id = [];
        foreach ($invoice_payments as $invoice_payment){
            $list_invoice_payment_id[] = $invoice_payment->id;
        }

        $invoice_detail_payments = new InvoiceDetailPayment();
        $invoice_detail_payments = $invoice_detail_payments->whereIn('invoice_payment_id', $list_invoice_payment_id);
        $invoice_detail_payments = $invoice_detail_payments->join('invoice_details', 'invoice_details.id', '=', 'invoice_detail_payments.invoice_detail_id');
        $invoice_detail_payments = $invoice_detail_payments->join('dues', 'dues.id', '=', 'invoice_details.due_id');
        $invoice_detail_payments = $invoice_detail_payments->select(
            "invoice_detail_payments.*",
            "dues.name as due_name",
        );

        if ($due_id != null) {
            $invoice_detail_payments = $invoice_detail_payments->where('invoice_details.due_id', "=", $due_id);
        }

        if ($classroom_id != null) {
            $invoice_detail_payments = $invoice_detail_payments->where('invoice_details.classroom_id', '=', $classroom_id);
        }

        $invoice_detail_payments = $invoice_detail_payments->get();
            // dd($invoice_detail_payments);

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "invoices_payments" => $invoice_payments,
            "invoice_detail_payments" => $invoice_detail_payments
        ];

        if ($request->type) {
            $fileName = "Laporan Detail Pembayaran Siswa";
            $view = 'pages.reports.student-paid-detail.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.student-paid-detail.print', $data);
    }

    public function student_over_paid_print(Request $request)
    {
        if (!UserInfoHelper::has_access("student_over_paid_report", "export")) return abort(404);

        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $invoices = new Invoice();
        $invoices = $invoices->leftJoin('students', 'students.id', '=', 'invoices.student_id');
        $invoices = $invoices->whereBetween('invoices.created_at', [$date_from . " 00:00:00", $date_to . " 23:59:59"]);
        $invoices = $invoices->whereRaw('invoices.payed_amount > invoices.price');
        $invoices = $invoices->groupBy('invoices.student_id', 'students.name', 'students.nis');
        $invoices = $invoices->select(
            "invoices.student_id",
            "students.name as student_name",
            "students.nis as student_nis",
            DB::raw("SUM(invoices.payed_amount) - SUM(invoices.price) as over_paid_amount")
        );
        $invoices = $invoices->get();

        $data = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "invoices" => $invoices
        ];


        if ($request->type) {
            $fileName = "Laporan Siswa Lebih Bayar";
            $view = 'pages.reports.student-over-paid.print';

            return ExportHelper::export(
                $request->type,
                $fileName,
                $data,
                $view,
                $request,
                [
                    'size' => 'legal',
                    'orientation' => 'landscape',
                ],
                $filters = []
            );
        }

        return view('pages.reports.student-over-paid.print', $data);
    }



    public function bank_daily(Request $request)
    {
        if (!UserInfoHelper::has_access("daily_bank_report", "view")) return abort(404);
        $banks = Bank::all();
        $data = [
            "banks" => $banks
        ];
        return view('pages.reports.bank-daily.index', $data);

    }

    // TODO: Cek laporan
    public function bank_daily_print(Request $request)
    {
        if (!UserInfoHelper::has_access("daily_bank_report", "view")) return abort(404);
        $result_pg = [];
        $result_tk = [];
        $result_sd = [];
        $result_smp = [];

        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $bca_payments = InvoiceDetailPayment::join("invoice_payments", "invoice_payments.id", "=", "invoice_detail_payments.invoice_payment_id")
            ->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id")
            ->join("classrooms", "classrooms.id", "=", "invoice_details.classroom_id")
            ->where('invoice_payments.bank_id', "=", Bank::BCA_BANK_ID)
            ->whereBetween('invoice_payments.date', [$date_from . " 00:00:00", $date_from . " 23:59:59"])
            ->select(
                "invoice_detail_payments.*",
                "invoice_payments.created_at",
                "invoice_payments.date",
                "invoice_details.due_id",
                "invoice_detail_payments.price",
                "invoice_detail_payments.refund_amount",
                "classrooms.school_group as school_group"
            )
            ->get();

        $maspion_payments = InvoiceDetailPayment::join("invoice_payments", "invoice_payments.id", "=", "invoice_detail_payments.invoice_payment_id")
            ->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id")
            ->join("classrooms", "classrooms.id", "=", "invoice_details.classroom_id")
            ->where('invoice_payments.bank_id', "=", Bank::MASPION_BANK_ID)
            ->whereBetween('invoice_payments.date', [$date_from . " 00:00:00", $date_from . " 23:59:59"])
            ->select(
                "invoice_detail_payments.*",
                "invoice_payments.created_at",
                "invoice_payments.date",
                "invoice_details.due_id",
                "invoice_detail_payments.price",
                "invoice_detail_payments.refund_amount",
                "classrooms.school_group as school_group"
            )
            ->get();

        $dues = Due::get();

        foreach ($dues as $due) {
            $result_pg[$due->id] = [
                'due_id' => $due->id,
                'due_name' => $due->name,
                'bca' => 0,
                'maspion' => 0,
            ];
            $result_tk[$due->id] = [
                'due_id' => $due->id,
                'due_name' => $due->name,
                'bca' => 0,
                'maspion' => 0,
            ];
            $result_sd[$due->id] = [
                'due_id' => $due->id,
                'due_name' => $due->name,
                'bca' => 0,
                'maspion' => 0,
            ];
            $result_smp[$due->id] = [
                'due_id' => $due->id,
                'due_name' => $due->name,
                'bca' => 0,
                'maspion' => 0,
            ];
        }

        foreach ($bca_payments as $bca_payment) {
            $school_group = strtolower($bca_payment->school_group);
            if ($school_group == "pg") {
                $result_pg[$bca_payment->due_id]['bca'] += $bca_payment->price - $bca_payment->refund_amount;
            } else if ($school_group == "tk") {
                $result_tk[$bca_payment->due_id]['bca'] += $bca_payment->price - $bca_payment->refund_amount;
            } else if ($school_group == "sd") {
                $result_sd[$bca_payment->due_id]['bca'] += $bca_payment->price - $bca_payment->refund_amount;
            } else if ($school_group == "smp") {
                $result_smp[$bca_payment->due_id]['bca'] += $bca_payment->price - $bca_payment->refund_amount;
            }
        }

        foreach ($maspion_payments as $maspion_payment) {
            $school_group = strtolower($maspion_payment->school_group);
            if ($school_group == "pg") {
                $result_pg[$maspion_payment->due_id]['maspion'] += $maspion_payment->price - $maspion_payment->refund_amount;
            } else if ($school_group == "tk") {
                $result_tk[$maspion_payment->due_id]['maspion'] += $maspion_payment->price - $maspion_payment->refund_amount;
            } else if ($school_group == "sd") {
                $result_sd[$maspion_payment->due_id]['maspion'] += $maspion_payment->price - $maspion_payment->refund_amount;
            } else if ($school_group == "smp") {
                $result_smp[$maspion_payment->due_id]['maspion'] += $maspion_payment->price - $maspion_payment->refund_amount;
            }
        }





        // Ambil data lebih bayar
        $over_paid_cashflows = FinanceCashFlow::where("note", "like", "Lebih Bayar%")
            ->whereNotNull("coa_sub_detail_name")
            ->whereBetween("transaction_date", [$date_from . " 00:00:00", $date_from . " 23:59:59"])
            ->select(
                "coa_sub_detail_name",
                "debit",
                "credit",
                "note"
            )
            ->get();

        // Ambil data kurang bayar
        $under_paid_cashflows = FinanceCashFlow::where("note", "like", "Kurang Bayar%")
            ->whereNotNull("coa_sub_detail_name")
            ->whereBetween("transaction_date", [$date_from . " 00:00:00", $date_from . " 23:59:59"])
            ->select(
                "coa_sub_detail_name",
                "debit",
                "credit",
                "note"
            )
            ->get();

        $result_over_paid = [];
        foreach ($over_paid_cashflows as $over_paid_cashflow) {
            $result_over_paid[] = [
                'coa_sub_detail_name' => $over_paid_cashflow->coa_sub_detail_name,
                'debit' => $over_paid_cashflow->debit,
                'credit' => $over_paid_cashflow->credit,
                'note' => $over_paid_cashflow->note,
            ];
        }

        $result_under_paid = [];
        foreach ($under_paid_cashflows as $under_paid_cashflow) {
            $result_under_paid[] = [
                'coa_sub_detail_name' => $under_paid_cashflow->coa_sub_detail_name,
                'debit' => $under_paid_cashflow->debit,
                'credit' => $under_paid_cashflow->credit,
                'note' => $under_paid_cashflow->note,
            ];
        }
        

        $results = [
            'pg' => $result_pg,
            'tk' => $result_tk,
            'sd' => $result_sd,
            'smp' => $result_smp,
            'over_paid' => $result_over_paid,
            'under_paid' => $result_under_paid,
        ];

        return view('pages.reports.bank-daily.print', [
            "date" => $date_from,
            "results" => $results,
        ]);
    }
}
