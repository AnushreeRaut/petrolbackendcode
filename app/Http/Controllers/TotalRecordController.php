<?php

namespace App\Http\Controllers;

use App\Models\TotalRecord;
use Illuminate\Http\Request;

class TotalRecordController extends Controller
{
    public function index()
    {
        return response()->json(TotalRecord::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stock_record_id' => 'required|integer',
            'sale_record_id' => 'required|integer',
            'bal_id' => 'required|integer',
            'added_by' => 'required|integer',
        ]);

        $totalRecord = TotalRecord::create($validated + $request->except(['stock_record_id', 'sale_record_id', 'bal_id', 'added_by']));

        return response()->json($totalRecord, 201);
    }

    public function show($id)
    {
        return response()->json(TotalRecord::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $totalRecord = TotalRecord::findOrFail($id);
        $validated = $request->validate([
            'updated_by' => 'integer',
        ]);

        $totalRecord->update($validated + $request->except(['updated_by']));

        return response()->json($totalRecord);
    }

    public function destroy($id)
    {
        $totalRecord = TotalRecord::findOrFail($id);
        $totalRecord->delete();

        return response()->json(['message' => 'Record deleted successfully.']);
    }
}
