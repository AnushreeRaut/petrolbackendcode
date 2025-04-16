<?php

namespace App\Http\Controllers;

use App\Models\StockInPiece;
use Illuminate\Http\Request;

class StockInPieceController extends Controller
{
    public function index()
    {
        return StockInPiece::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'oil_product_id' => 'required|integer|exists:oil_products,id', // Adjust based on your actual table name
            'grade' => 'required|string|max:100',
            'color' => 'required|string|max:50',
            'mrp' => 'required|numeric',
            'volume_per_pcs' => 'required|numeric',
            'vol_type' => 'required|string|max:50', // e.g., liters, gallons
            'pieces_purchase' => 'required|integer',
            'per_tcases' => 'required|integer',
            'total_pcs' => 'required|integer', // This should be calculated based on pieces_purchase and per_tcases
            'added_by' => 'required|integer'
        ]);

        return StockInPiece::create($request->all());
    }

    public function show($id)
    {
        return StockInPiece::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $stockInPiece = StockInPiece::findOrFail($id);

        $request->validate([
            // Same validation rules as in store method
        ]);

        $stockInPiece->update($request->all());

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        StockInPiece::destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
