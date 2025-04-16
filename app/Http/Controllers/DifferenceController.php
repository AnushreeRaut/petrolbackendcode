<?php

namespace App\Http\Controllers;

use App\Models\Difference;
use Illuminate\Http\Request;

class DifferenceController extends Controller
{
    public function index()
    {
        return Difference::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'oil_product_id' => 'required|integer|exists:oil_products,id', // Adjust based on your actual table name
            'volume_per_pcs' => 'required|numeric',
            'vol_type' => 'required|string|max:50', // e.g., liters, gallons
            'mrp_price' => 'required|numeric',
            'landing_price' => 'required|numeric',
            'difference_per_pc' => 'required|numeric',
            'added_by' => 'required|integer'
        ]);

        return Difference::create($request->all());
    }

    public function show($id)
    {
        return Difference::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $difference = Difference::findOrFail($id);

        $request->validate([
            // Same validation rules as in store method
        ]);

        $difference->update($request->all());

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        Difference::destroy($id);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
