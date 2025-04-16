<?php

namespace App\Http\Controllers;

use App\Models\InvoiceFeeding;
use Illuminate\Http\Request;

class InvoiceFeedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = InvoiceFeeding::with('tank')->get();
        return response()->json([
            'message' => 'Invoices retrieved successfully.',
            'data' => $invoices
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string|max:255',
            'tank_id' => 'required|exists:tanks,id',
            'kl_qty' => 'required|numeric',
            'rate_per_unit' => 'required|numeric',
            'value' => 'required|numeric',
            'taxable_amount' => 'required|numeric',
            'product_amount' => 'required|numeric',
            'vat_percent' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess' => 'required|numeric',
            'tcs' => 'required|numeric',
            't_amount' => 'required|numeric',
            't_invoice_amount' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $invoice = InvoiceFeeding::create($validated);

        return response()->json([
            'message' => 'Invoice created successfully.',
            'data' => $invoice
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceFeeding $invoiceFeeding)
    {
        return response()->json([
            'message' => 'Invoice retrieved successfully.',
            'data' => $invoiceFeeding->load('tank')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceFeeding $invoiceFeeding)
    {
        $validated = $request->validate([
            'invoice_no' => 'required|string|max:255',
            'tank_id' => 'required|exists:tanks,id',
            'kl_qty' => 'required|numeric',
            'rate_per_unit' => 'required|numeric',
            'value' => 'required|numeric',
            'taxable_amount' => 'required|numeric',
            'product_amount' => 'required|numeric',
            'vat_percent' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess' => 'required|numeric',
            'tcs' => 'required|numeric',
            't_amount' => 'required|numeric',
            't_invoice_amount' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $invoiceFeeding->update($validated);

        return response()->json([
            'message' => 'Invoice updated successfully.',
            'data' => $invoiceFeeding
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceFeeding $invoiceFeeding)
    {
        $invoiceFeeding->delete();

        return response()->json([
            'message' => 'Invoice deleted successfully.'
        ]);
    }
}
