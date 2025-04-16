<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRecord;
use Illuminate\Http\Request;

class PurchaseRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseRecords = PurchaseRecord::with('productDetail')->get();
        return response()->json([
            'message' => 'Purchase records retrieved successfully.',
            'data' => $purchaseRecords
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'value_1_ms' => 'required|numeric',
            'value_2_speed' => 'required|numeric',
            'value_3_hsd' => 'required|numeric',
            'total_kl' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $purchaseRecord = PurchaseRecord::create($validated);

        return response()->json([
            'message' => 'Purchase record created successfully.',
            'data' => $purchaseRecord
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRecord $purchaseRecord)
    {
        return response()->json([
            'message' => 'Purchase record retrieved successfully.',
            'data' => $purchaseRecord->load('productDetail')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRecord $purchaseRecord)
    {
        $validated = $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'value_1_ms' => 'required|numeric',
            'value_2_speed' => 'required|numeric',
            'value_3_hsd' => 'required|numeric',
            'total_kl' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $purchaseRecord->update($validated);

        return response()->json([
            'message' => 'Purchase record updated successfully.',
            'data' => $purchaseRecord
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRecord $purchaseRecord)
    {
        $purchaseRecord->delete();

        return response()->json([
            'message' => 'Purchase record deleted successfully.'
        ]);
    }
}
