<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestingPaymentController extends Controller
{
    //
    public function invoice_testing_get(Request $request)
    {
        return response()->json([
            "rq_uuid" => $request->rq_uuid,
            "rs_datetime" => date("Y-m-d H:i:s"),
            "error_code" => "0000",
            "error_message" => "Success",
            "va_number" => "123456789",
            "expired" => "2025-12-01 23:32:12",
            "description" => "Pembayaran X",
            "total_amount" => "120000",
            "amount" => "120000",
            "fee" => "0"
        ]);
    }

    public function invoice_testing_post(Request $request)
    {
        return response()->json([
            "rq_uuid" => $request->rq_uuid,
            "rs_datetime" => date("Y-m-d H:i:s"),
            "error_code" => "0000",
            "error_message" => "Success",
            "va_number" => "123456789",
            "expired" => "2025-12-01 23:32:12",
            "description" => "Pembayaran X",
            "total_amount" => "120000",
            "amount" => "120000",
            "fee" => "0"
        ]);
    }
}
