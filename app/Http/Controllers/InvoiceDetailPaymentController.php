<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailPayment;
use Illuminate\Http\Request;

class InvoiceDetailPaymentController extends Controller
{
    public function do_invoice_payment_detail_update($id, Request $request)
    {
        $result = InvoiceDetail::do_update($id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function do_invoice_payment_detail_delete($id, Request $request)
    {
        $result = InvoiceDetail::do_destroy($id, $request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
