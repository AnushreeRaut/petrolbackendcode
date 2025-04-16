<?php

namespace App\Http\Controllers;

use App\Models\IndexInvoice;
use Illuminate\Http\Request;

class IndexInvoiceController extends Controller
{
    public function index()
    {
        return IndexInvoice::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_feeding_id' => 'required|integer|exists:invoice_feedings,id', // Adjust based on your actual table name
            'product_name' => 'required|string|max:255',
            'rate_per_unit' => 'required|numeric',
            'taxable_amount' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess' => 'required|numeric',
            'tcs' => 'required|numeric',
            'tds' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'lfr' => 'required|numeric',
            'added_by' => 'required|integer'
        ]);

        return IndexInvoice::create($request->all());
    }

    public function show($id)
    {
        return IndexInvoice::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $indexInvoice = IndexInvoice::findOrFail($id);

        $request->validate([
            // Same validation rules as in store method
        ]);

        $indexInvoice->update($request->all());

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        IndexInvoice::destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
