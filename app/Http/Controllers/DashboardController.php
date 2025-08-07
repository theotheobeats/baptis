<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsappNotificationHelper;
use App\Models\ApiEspayPaymentNotification;
use App\Models\FinanceCashFlow;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public static $information = [
        "title" => "Dashboard",
        "route" => "/",
        "view" => "pages.dashboard"
    ];

    public function index(Request $request)
    {
        $student_count = Student::count();
        $student_active_count = Student::whereNull('non_active_at')->count();
        $student_late_payment = DB::table('invoice_details')->select(DB::raw("COUNT(invoice_amount.amount) AS amount"))
            ->from(DB::raw("(SELECT COUNT(backtrack_student_id) AS amount FROM invoice_details WHERE payment_due_date < '" . now() . "' AND deleted_at IS NULL GROUP BY backtrack_student_id) AS invoice_amount"))
            ->first();
        // dd($student_late_payment);
        $today_income = FinanceCashFlow::whereDate('created_at', now())->where('debit', '>', 0)->sum('debit');
        $virtual_account = InvoicePayment::where('bank_id', '!=', 1)->count();
        $whatsapp_bot = WhatsappNotificationHelper::get_bot();

        return view("/pages/dashboard", [
            "information" => self::$information,
            "student_count" => $student_count,
            "student_active_count" => $student_active_count,
            "student_late_payment" => $student_late_payment,
            "today_income" => $today_income,
            "virtual_account" => $virtual_account,
            "whatsapp_bot" => $whatsapp_bot
        ]);
    }

    public function table_api_espay_payment_notifications_maspion(Request $request)
    {
        $api_espay_payment_notifications = new ApiEspayPaymentNotification();
        $api_espay_payment_notifications = $api_espay_payment_notifications->join('invoice_reconciliations', 'invoice_reconciliations.maspion_va_number', '=', 'api_espay_payment_notifications.order_id');
        $api_espay_payment_notifications = $api_espay_payment_notifications->select("api_espay_payment_notifications.*");
        return DataTables::of($api_espay_payment_notifications)
            ->addIndexColumn()
            ->editColumn('ss_json', function ($data) {
                $ss_json = $data->ss_json;
                if (strlen($ss_json) > 40) {
                    $ss_json = substr($ss_json, 0, 40) . "...";
                }
                return "<a onclick='show(`" . $data->ss_json . "`)'>$ss_json</a>";
            })
            ->editColumn('reconcile_datetime', function ($data) {
                if ($data->reconcile_datetime == null) return "";
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->reconcile_datetime)->translatedFormat('d F Y - H:i:s');
            })
            ->editColumn('amount', function ($data) {
                return number_format($data->amount, 0, ',', '.');
            })
            ->rawColumns(['ss_json'])
            ->make(true);
    }
}
