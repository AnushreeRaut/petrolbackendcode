<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissions = Commission::with('invoiceFeeding')->get();
        return response()->json([
            'message' => 'Commissions retrieved successfully.',
            'data' => $commissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'total_amount' => 'required|numeric',
            'kl_liters' => 'required|numeric',
            'purchase_per_liter' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'diff_comm' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $commission = Commission::create($validated);

        return response()->json([
            'message' => 'Commission created successfully.',
            'data' => $commission
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        return response()->json([
            'message' => 'Commission retrieved successfully.',
            'data' => $commission->load('invoiceFeeding')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'total_amount' => 'required|numeric',
            'kl_liters' => 'required|numeric',
            'purchase_per_liter' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'diff_comm' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $commission->update($validated);

        return response()->json([
            'message' => 'Commission updated successfully.',
            'data' => $commission
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission)
    {
        $commission->delete();

        return response()->json([
            'message' => 'Commission deleted successfully.'
        ]);
    }
}
