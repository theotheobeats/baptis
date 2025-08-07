<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Models\InvoiceDetail;
use App\Models\PaymentRefund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\UserInfoHelper;
use App\Models\InvoiceDetailPayment;
use App\Models\InvoicePayment;
use App\Models\PaymentRefundDetail;
use Illuminate\Support\Facades\DB;

class PaymentRefundController extends Controller
{
    public static $information = [
        "title" => "Refund Tagihan",
        "route" => "/transaction/payment-refund",
        "view" => "pages.transactions.payment-refund."
    ];


    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("payment_refund", "view")) return abort(404);
        if ($request->ajax()) {
            $payment_refunds = new PaymentRefund();
            $payment_refunds = $payment_refunds->leftJoin("students", "students.id", "=", "payment_refunds.student_id");
            // $payment_refunds = $payment_refunds->leftJoin("finance_accounts", "finance_accounts.id", "=", "payment_refunds.cash_account_id");
            $payment_refunds = $payment_refunds->leftJoin("banks", "banks.id", "=", "payment_refunds.bank_id");
            $payment_refunds = $payment_refunds->select("payment_refunds.*", "students.name as student_name", "banks.name as bank_name");
            return DataTables::of($payment_refunds)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $view_detail_action = 'detail_modal("' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";

                    $btn .= "<a class='btn btn-outline-primary' href='#' onclick='$view_detail_action' title='Lihat'><i class='fa fa-eye'></i></a>";
                    // $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    // $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
                    $btn .= "</div>";
                    return $btn;
                })
                ->editColumn('total', function ($row) {
                    return "Rp " . number_format($row->total, 0, ",", ".");
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view(self::$information['view'] . 'index', [
            "information" => self::$information
        ]);
    }

    public function create()
    {
        if (!UserInfoHelper::has_access("payment_refund", "add")) return abort(404);
        return view(self::$information['view'] . 'add', [
            "information" => self::$information
        ]);
    }


    //
    public function store(Request $request)
    {
        if (!UserInfoHelper::has_access("payment_refund", "add")) return abort(404);
        // $decrypt = CryptoHelper::decrypt($request->id);
        // if (!$decrypt->success) return $decrypt->error_response;

        $result = PaymentRefund::do_store($request);
        return response()->json($result["client_response"], $result["code"]);
    }


    public function get_invoice_payment(Request $request)
    {
        $student_id = $request->student_id;
        $month = $request->month;
        $year = $request->year;

        $invoice_detail_payments = new InvoiceDetailPayment;
        $invoice_detail_payments = $invoice_detail_payments->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        $invoice_detail_payments = $invoice_detail_payments->join("invoice_payments", "invoice_payments.id", "=", "invoice_detail_payments.invoice_payment_id");
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_detail_payments.student_id", "=", $student_id);
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_details.payment_for_month", "=", $month);
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_details.payment_for_year", "=", $year);
        $invoice_detail_payments = $invoice_detail_payments->where(DB::raw("invoice_detail_payments.refund_amount < invoice_detail_payments.price"));
        $invoice_detail_payments = $invoice_detail_payments->select(
            "invoice_detail_payments.*",
            "invoice_detail_payments.id as invoice_detail_payment_id",
            "invoice_details.code as invoice_detail_code",
            "invoice_details.payment_for_month as payment_for_month",
            "invoice_details.payment_for_year as payment_for_year",
            "invoice_payments.code as payment_code"
        );
        $invoice_detail_payments = $invoice_detail_payments->get();

        return response()->json([
            "invoice_detail_payments" => $invoice_detail_payments
        ]);
    }


    // Cari tagihan yang sudah dibayar dan mau di refund
    public function find_student_due_payment(Request $request)
    {
        $student_id = $request->student_id;
        $due_id = $request->due_id;
        $month = $request->month;
        $year = $request->year;

        // Cari tagihan terkait
        $invoice_detail = new InvoiceDetail;
        $invoice_detail = $invoice_detail->where("backtrack_student_id", "=", $student_id);
        $invoice_detail = $invoice_detail->where("due_id", "=", $due_id);
        $invoice_detail = $invoice_detail->where("payment_for_month", "=", $month);
        $invoice_detail = $invoice_detail->where("payment_for_year", "=", $year);
        $invoice_detail = $invoice_detail->first();

        // Cek tagihan ada
        if ($invoice_detail == null) {
            return response()->json(ResponseHelper::response_error("Pencarian Gagal", "Tidak ada tagihan terkait kriteria dipilih", 406)["client_response"], 406);
        }

        // Cek apakah tagihan sudah dibayar
        if ($invoice_detail->price > $invoice_detail->payed_amount) {
            return response()->json(ResponseHelper::response_error("Pencarian Gagal", "Tagihan belum dibayar", 406)["client_response"], 406);
        }

        $result = [
            "invoice_detail_id" => Crypt::encrypt($invoice_detail->id),
            "price" => $invoice_detail->price,
        ];

        return response()->json([
            "invoice_detail" => $result
        ]);
    }


    // Ambil data Payment Refund Detail berdasarkan payment refund id
    public function get_payment_refund_detail(Request $request)
    {
        $payment_refund_id = Crypt::decrypt($request->payment_refund_id);

        $payment_refund = PaymentRefund::find($payment_refund_id);
        $payment_refund_details = new PaymentRefundDetail();
        $payment_refund_details = $payment_refund_details->join("invoice_detail_payments", "invoice_detail_payments.id", "=", "payment_refund_details.invoice_detail_payment_id");
        $payment_refund_details = $payment_refund_details->join("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        $payment_refund_details = $payment_refund_details->join("invoice_payments", "invoice_payments.id", "=", "invoice_detail_payments.invoice_payment_id");
        $payment_refund_details = $payment_refund_details->where("payment_refund_id", "=", $payment_refund_id);
        $payment_refund_details = $payment_refund_details->select(
            "payment_refund_details.id",
            "payment_refund_details.amount",
            "invoice_details.code as invoice_detail_code",
            "invoice_details.payment_for_month as payment_for_month",
            "invoice_details.payment_for_year as payment_for_year",
            "invoice_payments.code as payment_code"
        );
        $payment_refund_details = $payment_refund_details->get();

        return response()->json([
            "payment_refund_details" => $payment_refund_details,
            "payment_refund" => $payment_refund
        ]);
    }
}
