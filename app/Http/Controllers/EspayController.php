<?php

namespace App\Http\Controllers;

use App\Helpers\InvoiceHelper;
use App\Helpers\InvoicePublisherHelper;
use App\Helpers\PaymentGatewayHelper;
use App\Helpers\WhatsappNotificationHelper;
use App\Models\ApiEspayPaymentNotification;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceReconciliation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class EspayController extends BaseController
{
    public function receive_payment_notification(Request $request)
    {
        // Close invoice
        // Cek ada tagihan tersisa?
        // Jika ada maka publish
        
        $payment_notification = new ApiEspayPaymentNotification;
        $payment_notification->success_flag = "";
        $payment_notification->error_message = "";
        $payment_notification->reconcile_id = "";
        $payment_notification->order_id = $request->order_id;
        $payment_notification->reconcile_datetime = $request->reconcile_datetime;
        $payment_notification->amount = $request->amount;
        $payment_notification->ss_json = json_encode($request->all());

        $success_flag = "";
        $error_message = "";
        $reconcile_id = "";
        $order_id = "";
        $reconcile_datetime = "";

        if ($payment_notification->save()) {
            $success_flag = "1";
            $error_message = "Success";
            $reconcile_id = $payment_notification->id;
            $order_id = $payment_notification->order_id;
            $amount = $payment_notification->amount;
            $reconcile_datetime = $payment_notification->created_at;

            // Lakukan pembayaran
            // Cari apakah ada order_id yang sesuai
            $invoice_reconciliation = InvoiceReconciliation::whereNull("inactive_at")
                ->where("maspion_va_number", "=", $order_id)
                ->first();

            if ($invoice_reconciliation != null) {
                // Lakukan Pembayaran
                $invoice_payment = InvoicePayment::do_payment($invoice_reconciliation->invoice_id, $amount, 2, $request->rq_uuid);

                // Close Invoice Reconciliation
                $invoice_reconciliation->inactive_at = now();
                $invoice_reconciliation->save();

                // Close tagihan bank maspion (TODO: BCA juga)
                PaymentGatewayHelper::maspion_close_invoice($order_id);

            }
        } else {
            $success_flag = 0;
            $error_message = "Invalid request";
        }

        return "$success_flag,$error_message,$reconcile_id,$order_id,$reconcile_datetime";
    }

}
