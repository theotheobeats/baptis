<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    public function publish_monthly_invoice(Request $request)
    {
        // $publish_invoice = Invoice::publish_monthly_invoice($request);
        $result = Invoice::improved_publish_monthly_invoice($request);
        return response()->json($result["client_response"], $result["code"]);
    }

    public function publish_individual_invoice(Request $request)
    {
        // $publish_invoice = Invoice::publish_monthly_invoice($request);
        $result = Invoice::improved_publish_individual_invoice($request);
        return response()->json($result["client_response"], $result["code"]);
    }
}
