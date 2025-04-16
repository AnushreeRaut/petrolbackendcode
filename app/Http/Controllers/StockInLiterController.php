<?php

namespace App\Http\Controllers;

use App\Models\StockInLiter;
use Illuminate\Http\Request;

class StockInLiterController extends Controller
{
    public function index()
    {
        return StockInLiter::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'oil_product_id' => 'required|integer|exists:oil_products,id', // Adjust based on your actual table name
            'vol_per_pcs' => 'required|numeric',
            'vol_type' => 'required|string|max:50', // e.g., liters, gallons
            'total_liters' => 'required|numeric',
            'perunit_price' => 'required|numeric',
            'taxable_value' => 'required|numeric',
            'added_by' => 'required|integer'
        ]);

        return StockInLiter::create($request->all());
    }

    public function show($id)
    {
        return StockInLiter::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $stockInLiter = StockInLiter::findOrFail($id);

        $request->validate([
            // Same validation rules as in store method
        ]);

        $stockInLiter->update($request->all());

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        StockInLiter::destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
