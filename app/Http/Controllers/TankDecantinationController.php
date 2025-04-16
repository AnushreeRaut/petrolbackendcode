<?php

namespace App\Http\Controllers;

use App\Models\TankDecantination;
use Illuminate\Http\Request;

class TankDecantinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tankDecantinations = TankDecantination::with('productDetail')->get();
        return response()->json([
            'message' => 'Tank decantations retrieved successfully.',
            'data' => $tankDecantinations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'tank_1_ms' => 'required|numeric',
            'tank_2_speed' => 'required|numeric',
            'tank_3_hsd' => 'required|numeric',
            'total_kl' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $tankDecantination = TankDecantination::create($validated);

        return response()->json([
            'message' => 'Tank decantation created successfully.',
            'data' => $tankDecantination
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TankDecantination $tankDecantination)
    {
        return response()->json([
            'message' => 'Tank decantation retrieved successfully.',
            'data' => $tankDecantination->load('productDetail')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TankDecantination $tankDecantination)
    {
        $validated = $request->validate([
            'product_detail_id' => 'required|exists:product_details,id',
            'tank_1_ms' => 'required|numeric',
            'tank_2_speed' => 'required|numeric',
            'tank_3_hsd' => 'required|numeric',
            'total_kl' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $tankDecantination->update($validated);

        return response()->json([
            'message' => 'Tank decantation updated successfully.',
            'data' => $tankDecantination
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TankDecantination $tankDecantination)
    {
        $tankDecantination->delete();

        return response()->json([
            'message' => 'Tank decantation deleted successfully.'
        ]);
    }
}
