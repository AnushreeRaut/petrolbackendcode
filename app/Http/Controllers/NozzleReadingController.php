<?php

namespace App\Http\Controllers;

use App\Models\NozzleReading;
use Illuminate\Http\Request;

class NozzleReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nozzleReadings = NozzleReading::with('invoiceFeeding')->get();
        return response()->json([
            'message' => 'Nozzle readings retrieved successfully.',
            'data' => $nozzleReadings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'nozzle_name' => 'required|string|max:255',
            'opening' => 'required|numeric',
            'closing' => 'required|numeric',
            'sale' => 'required|numeric',
            'testing' => 'required|numeric',
            'a_sale' => 'required|numeric',
            'total_amt' => 'required|numeric',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $nozzleReading = NozzleReading::create($validated);

        return response()->json([
            'message' => 'Nozzle reading created successfully.',
            'data' => $nozzleReading
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(NozzleReading $nozzleReading)
    {
        return response()->json([
            'message' => 'Nozzle reading retrieved successfully.',
            'data' => $nozzleReading->load('invoiceFeeding')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NozzleReading $nozzleReading)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'nozzle_name' => 'required|string|max:255',
            'opening' => 'required|numeric',
            'closing' => 'required|numeric',
            'sale' => 'required|numeric',
            'testing' => 'required|numeric',
            'a_sale' => 'required|numeric',
            'total_amt' => 'required|numeric',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $nozzleReading->update($validated);

        return response()->json([
            'message' => 'Nozzle reading updated successfully.',
            'data' => $nozzleReading
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NozzleReading $nozzleReading)
    {
        $nozzleReading->delete();

        return response()->json([
            'message' => 'Nozzle reading deleted successfully.'
        ]);
    }
}
