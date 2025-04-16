<?php

namespace App\Http\Controllers;

use App\Models\Summary;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index()
    {
        return Summary::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'oil_product_id' => 'required|integer|exists:oil_products,id', // Adjust based on your actual table name
            'stock_in_liters' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'tcs' => 'required|numeric',
            'total_amt' => 'required|numeric',
            'total_pcs' => 'required|integer',
            'landing_price' => 'required|numeric',
            'purchase_amt' => 'required|numeric',
            'other_discount' => 'nullable|numeric',
            'invoice_amt' => 'required|numeric',
            'added_by' => 'required|integer'
        ]);

        return Summary::create($request->all());
    }

    public function show($id)
    {
        return Summary::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $summary = Summary::findOrFail($id);

        $request->validate([
            // Same validation rules as in store method
        ]);

        $summary->update($request->all());

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        Summary::destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
