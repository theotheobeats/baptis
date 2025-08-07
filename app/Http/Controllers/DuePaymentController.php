<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\UserInfoHelper;
use App\Helpers\WhatsappNotificationHelper;
use App\Imports\DueImport;
use App\Imports\PositionImport;
use App\Models\Bank;
use App\Models\CashAccount;
use App\Models\Due;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailPayment;
use App\Models\InvoicePayment;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DuePaymentController extends Controller
{

    public static $information = [
        "title" => "Pembayaran Tagihan",
        "route" => "/transaction/due-payment",
        "view" => "pages.transactions.due-payment."
    ];


    // ======================
    // Line View - START
    // ======================


    // Menampilkan form input data
    public function index(Request $request)
    {
        if (!UserInfoHelper::has_access("due_payment", "view")) return abort(404);

        $cash_account = CashAccount::whereNull("close_time")->get();

        if (count($cash_account) == 0) {
            return view('/pages/finance/cashflow/open-cash-account', [
                "type" => "due-payment",
            ]);
        }

        if ($request->student_id != null) {
            $student_id = Crypt::decrypt($request->student_id);
            $student = Student::find($student_id);
            $student_text = $student->nis . "-" . $student->name . " - " . $student->backtrack_current_classroom;
        }

        $students = Student::select("id", "name", "nisn")->get();
        $banks = Bank::select("id", "name")->get();

        return view(self::$information['view'] . 'index', [
            "information" => self::$information,
            "students" => $students,
            "banks" => $banks,
            "student_id" => $student_id ?? null,
            "student_text" => $student_text ?? null,
        ]);
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $invoice_payments = InvoicePayment::leftJoin("invoices", "invoices.id", "=", "invoice_payments.invoice_id")
                ->leftJoin("students", "students.id", "=", "invoices.student_id")
                ->leftJoin("banks", "banks.id", "=", "invoice_payments.bank_id")
                ->select("invoice_payments.*", "banks.name as bank_name", "students.name as student_name")
                ->get();

            return DataTables::of($invoice_payments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encrypted_id = Crypt::encrypt($row->id);
                    $url = url(self::$information['route'] . '/edit') . '/' . $encrypted_id;
                    // $delete_action = 'delete_confirm("' . url(self::$information['route'] . '/delete') . '/' . $encrypted_id . '")';
                    $btn = "<div class='btn-group m-0'>";
                    $btn .= "<a class='btn btn-outline-primary' href='$url' title='Edit Data'><i class='fa fa-pencil'></i></a>";
                    // $btn .= "<a class='btn btn-outline-danger' href='#' onclick='$delete_action' title='Hapus Data'><i class='fa fa-trash'></i></a>";
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

        return view(self::$information['view'] . 'history', [
            "information" => self::$information
        ]);
    }


    public function get_student_active_invoice(Request $request)
    {
        $student_id = $request->student_id;
        $invoices = Invoice::where("student_id", "=", $student_id)
            ->select(
                "invoices.id as invoice_id",
                "invoices.price as invoice_price",
                "invoices.payed_amount as invoice_payed_amount",
                "invoices.price as invoice_price",
                "invoices.price as invoice_price",
                "invoices.price as invoice_price",
                "invoices.price as invoice_price"
            )
            ->get();

        return response()->json([
            "invoices" => $invoices
        ]);
    }

    public function get_student_active_due(Request $request)
    {
        $student_id = $request->student_id;

        $invoice = Invoice::where("student_id", "=", $student_id)->first();
        $invoice_id = $invoice == null ? null : Crypt::encrypt($invoice->id);

        $student_dues = new InvoiceDetail;
        $student_dues = $student_dues->leftJoin("invoices", "invoices.id", "=", "invoice_details.invoice_id");
        $student_dues = $student_dues->leftJoin("dues", "dues.id", "=", "invoice_details.due_id");
        $student_dues = $student_dues->where("invoices.student_id", "=", $student_id);
        $student_dues = $student_dues->whereRaw("invoice_details.price > invoice_details.payed_amount");
        $student_dues = $student_dues->select(
            "invoice_details.id",
            "invoice_details.due_id",
            "invoice_details.price",
            "invoice_details.payed_amount",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
            "invoice_details.payment_due_date",
            "dues.name as due_name",
        );
        $student_dues = $student_dues->get();

        return response()->json([
            "invoice_id" => $invoice_id,
            "student_dues" => $student_dues
        ]);
    }

    public function get_student_paid_due_per_month(Request $request)
    {
        $student_id = $request->student_id;

        $invoice_details = new InvoiceDetail();
        $invoice_details = $invoice_details->where("invoice_details.backtrack_student_id", "=", $student_id);
        $invoice_details = $invoice_details->leftJoin("students", "students.id", "=", "invoice_details.backtrack_student_id");
        $invoice_details = $invoice_details->leftJoin("classrooms", "classrooms.id", "=", "invoice_details.classroom_id");
        $invoice_details = $invoice_details->leftJoin("school_years", "school_years.id", "=", "invoice_details.school_year_id");
        $invoice_details = $invoice_details->select(
            "invoice_details.invoice_id",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
            "students.nis as student_nis",
            "students.name as student_name",
            "classrooms.name as classroom_name",
            DB::raw("CONCAT(school_years.name, ' ', school_years.semester) as school_year_name"),
            DB::raw("SUM(invoice_details.price) as total_price"),
            DB::raw("SUM(invoice_details.payed_amount) as total_payed_amount")
        );
        $invoice_details = $invoice_details->groupBy(
            "invoice_details.invoice_id",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
            "students.nis",
            "students.name",
            "classrooms.name",
            "school_years.name",
            "school_years.semester"
        );
        $invoice_details = $invoice_details->orderBy("invoice_details.invoice_id", "desc");
        $invoice_details = $invoice_details->get();

        foreach ($invoice_details as $invoice_detail) {
            $invoice_detail->invoice_id = Crypt::encrypt($invoice_detail->invoice_id);
        }

        return response()->json([
            "student_dues" => $invoice_details
        ]);
    }

    public function get_student_paid_due_detail(Request $request)
    {
        $invoice_id = Crypt::decrypt($request->invoice_id);
        // dd($request->all(), $invoice_id);

        $student_dues = new InvoiceDetail;
        $student_dues = $student_dues->leftJoin("invoices", "invoices.id", "=", "invoice_details.invoice_id");
        $student_dues = $student_dues->leftJoin("dues", "dues.id", "=", "invoice_details.due_id");
        $student_dues = $student_dues->where("invoices.id", "=", $invoice_id);
        $student_dues = $student_dues->where("invoice_details.payment_for_month", "=", str_pad($request->payment_for_month, 2, "0", STR_PAD_LEFT));
        $student_dues = $student_dues->where("invoice_details.payment_for_year", "=", $request->payment_for_year);
        $student_dues = $student_dues->select(
            "invoice_details.id",
            "invoice_details.price",
            "invoice_details.payed_amount",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
            "invoice_details.payment_due_date",
            "dues.name as due_name",
        );
        $student_dues = $student_dues->get();

        return response()->json([
            "invoice_id" => $invoice_id,
            "student_dues" => $student_dues
        ]);
    }

    public function get_student_paid_due(Request $request)
    {
        $student_id = $request->student_id;

        $invoice = Invoice::where("student_id", "=", $student_id)->first();
        $invoice_id = $invoice == null ? null : Crypt::encrypt($invoice->id);

        $student_dues = new InvoiceDetail;
        $student_dues = $student_dues->leftJoin("invoices", "invoices.id", "=", "invoice_details.invoice_id");
        $student_dues = $student_dues->leftJoin("dues", "dues.id", "=", "invoice_details.due_id");
        $student_dues = $student_dues->where("invoices.student_id", "=", $student_id);
        $student_dues = $student_dues->whereRaw("invoice_details.payed_amount >= invoice_details.price");
        $student_dues = $student_dues->select(
            "invoice_details.id",
            "invoice_details.price",
            "invoice_details.payed_amount",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
            "invoice_details.payment_due_date",
            "dues.name as due_name",
        );
        $student_dues = $student_dues->get();

        return response()->json([
            "invoice_id" => $invoice_id,
            "student_dues" => $student_dues
        ]);
    }

    public function get_student_payment_history(Request $request)
    {
        $student_id = $request->student_id;

        $invoice_payments = InvoicePayment::leftJoin("invoices", "invoices.id", "=", "invoice_payments.invoice_id")
            ->leftJoin("students", "students.id", "=", "invoices.student_id")
            ->leftJoin("banks", "banks.id", "=", "invoice_payments.bank_id")
            ->where("invoices.student_id", "=", $student_id)
            ->select("invoice_payments.*", "banks.name as bank_name", "students.name as student_name")
            ->orderBy("invoice_payments.created_at", "desc")
            ->get();

        foreach ($invoice_payments as $invoice_payment) {
            $invoice_payment->invoice_payment_id = Crypt::encrypt($invoice_payment->id);
            $invoice_payment->formatted_created_at = Carbon::createFromFormat('Y-m-d H:i:s', $invoice_payment->created_at)->translatedFormat('d F Y - H:i:s');
        }

        return response()->json([
            "invoice_payments" => $invoice_payments
        ]);
    }

    public function get_student_payment_history_detail(Request $request)
    {
        $invoice_payment_id = Crypt::decrypt($request->invoice_payment_id);

        $invoice_detail_payments = new InvoiceDetailPayment();
        $invoice_detail_payments = $invoice_detail_payments->leftJoin("invoice_details", "invoice_details.id", "=", "invoice_detail_payments.invoice_detail_id");
        $invoice_detail_payments = $invoice_detail_payments->leftJoin("dues", "dues.id", "=", "invoice_details.due_id");
        $invoice_detail_payments = $invoice_detail_payments->where("invoice_detail_payments.invoice_payment_id", "=", $invoice_payment_id);
        $invoice_detail_payments = $invoice_detail_payments->select(
            "invoice_detail_payments.id",
            "invoice_detail_payments.price",
            "dues.name as due_name",
            "invoice_details.payment_for_month",
            "invoice_details.payment_for_year",
        );
        $invoice_detail_payments = $invoice_detail_payments->get();

        return response()->json([
            "invoice_detail_payments" => $invoice_detail_payments
        ]);
    }

    public function send_wa_student_payment_history(Request $request)
    {
        $invoice_payment_id = Crypt::decrypt($request->invoice_payment_id);

        $invoice_payment = InvoicePayment::find($invoice_payment_id);

        // Generate file bukti pembayaran
        $payment_proof_path = InvoicePayment::generate_payment_proof($invoice_payment->id);

        $invoice = Invoice::withTrashed()->find($invoice_payment->invoice_id);
        $student = Student::withTrashed()->find($invoice->student_id);

        // Kirim WA bukti bayar
        WhatsappNotificationHelper::send_payment_proof_template_custom(
            [
                "user_name" => "dewi",
                "number" => $student->backtrack_student_whatsapp_number,
                "content_header" => url($payment_proof_path),
                "variabel" => [
                    "{{1}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('DD MMMM YYYY'),
                    "{{2}}" => "(text)" . $student->name,
                    "{{3}}" => "(text)" . \Carbon\Carbon::parse($invoice_payment->created_at)->isoFormat('MMMM YYYY'),
                    "{{4}}" => "(text)" . $payment_proof_path,
                ]
            ]
        );

        return ResponseHelper::response_success("Proses Kirim WhatsApp Berhasil", "Data telah diproses untuk dikirim ke nomor WhatsApp siswa (" . $student->backtrack_student_whatsapp_number . ")");
    }
}
