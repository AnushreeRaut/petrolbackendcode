<?php

namespace App\Http\Controllers;

use App\Models\StockRecord;
use Illuminate\Http\Request;

class StockRecordController extends Controller
{
    public function index()
    {
        return response()->json(StockRecord::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'oil_product_id' => 'required|integer',
            'product_name' => 'required|string',
            'volume' => 'required|numeric',
            'rate' => 'required|numeric',
            'added_by' => 'required|integer',
        ]);

        $stockRecord = StockRecord::create($validated);

        return response()->json($stockRecord, 201);
    }

    public function show($id)
    {
        return response()->json(StockRecord::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $stockRecord = StockRecord::findOrFail($id);
        $validated = $request->validate([
            'product_name' => 'string',
            'volume' => 'numeric',
            'rate' => 'numeric',
            'updated_by' => 'integer',
        ]);

        $stockRecord->update($validated);

        return response()->json($stockRecord);
    }

    public function destroy($id)
    {
        $stockRecord = StockRecord::findOrFail($id);
        $stockRecord->delete();

        return response()->json(['message' => 'Record deleted successfully.']);
    }
}
