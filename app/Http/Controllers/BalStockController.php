<?php

namespace App\Http\Controllers;

use App\Models\BalStock;
use Illuminate\Http\Request;

class BalStockController extends Controller
{
    public function index()
    {
        return response()->json(BalStock::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'oil_product_id' => 'required|integer',
            'added_by' => 'required|integer',
        ]);

        $balStock = BalStock::create($validated + $request->except(['oil_product_id', 'added_by']));

        return response()->json($balStock, 201);
    }

    public function show($id)
    {
        return response()->json(BalStock::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $balStock = BalStock::findOrFail($id);
        $validated = $request->validate([
            'updated_by' => 'integer',
        ]);

        $balStock->update($validated + $request->except(['updated_by']));

        return response()->json($balStock);
    }

    public function destroy($id)
    {
        $balStock = BalStock::findOrFail($id);
        $balStock->delete();

        return response()->json(['message' => 'Record deleted successfully.']);
    }
}
