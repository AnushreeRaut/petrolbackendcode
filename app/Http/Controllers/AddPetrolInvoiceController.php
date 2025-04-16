<?php

namespace App\Http\Controllers;

use App\Models\AddPetrolInvoice;
use App\Models\Tank;
use Illuminate\Http\Request;

class AddPetrolInvoiceController extends Controller
{

    public function getTankProducts()
    {
        $tanks = Tank::all(); // Fetch all tanks
        $products = $tanks->pluck('product', 'id'); // Get product names with tank IDs as keys
        return response()->json($products); // Return products as JSON
    }

    public function index()
    {
        $invoices = AddPetrolInvoice::with('tank')->get(); // Use get() to return an array of results
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tank_id' => 'required|exists:tanks,id',
            'rate_per_unit' => 'required|numeric',
            'tax_amt_per_amt' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess_per_unit' => 'required|numeric',
            'tcs_per_unit' => 'required|numeric',
            '194Q_tds' => 'required|numeric',
            'LFR_prt_kl' => 'required|numeric',
            'Cgst' => 'required|numeric',
            'SGST' => 'required|numeric',
            '194I_tds_lfr' => 'required|numeric',
        ]);

        $invoice = AddPetrolInvoice::create($request->all());
        return response()->json($invoice, 201);
    }

    public function show($id)
    {
        $invoice = AddPetrolInvoice::findOrFail($id);
        return response()->json($invoice);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tank_id' => 'required|exists:tanks,id',
            'rate_per_unit' => 'required|numeric',
            'tax_amt_per_amt' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess_per_unit' => 'required|numeric',
            'tcs_per_unit' => 'required|numeric',
            '194Q_tds' => 'required|numeric',
            'LFR_prt_kl' => 'required|numeric',
            'Cgst' => 'required|numeric',
            'SGST' => 'required|numeric',
            '194I_tds_lfr' => 'required|numeric',
        ]);

        $invoice = AddPetrolInvoice::findOrFail($id);
        $invoice->update($request->all());
        return response()->json($invoice);
    }

    public function destroy($id)
    {
        $invoice = AddPetrolInvoice::findOrFail($id);
        $invoice->delete();
        return response()->json(null, 204);
    }

    public function toggleStatus($id)
    {
        $invoice = AddPetrolInvoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found.'], 404);
        }

        $invoice->is_active = !$invoice->is_active;
        $invoice->save();

        return response()->json([
            'message' => 'Invoice status updated successfully.',
            'is_active' => $invoice->is_active,
        ]);
    }
}
