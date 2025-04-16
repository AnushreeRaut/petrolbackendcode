<?php

namespace App\Http\Controllers;

use App\Models\SalesRecord;
use Illuminate\Http\Request;

class SalesRecordController extends Controller
{
    public function index()
    {
        return response()->json(SalesRecord::all());
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

        $salesRecord = SalesRecord::create($validated);

        return response()->json($salesRecord, 201);
    }

    public function show($id)
    {
        return response()->json(SalesRecord::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $salesRecord = SalesRecord::findOrFail($id);
        $validated = $request->validate([
            'product_name' => 'string',
            'volume' => 'numeric',
            'rate' => 'numeric',
            'updated_by' => 'integer',
        ]);

        $salesRecord->update($validated);

        return response()->json($salesRecord);
    }

    public function destroy($id)
    {
        $salesRecord = SalesRecord::findOrFail($id);
        $salesRecord->delete();

        return response()->json(['message' => 'Record deleted successfully.']);
    }
}
