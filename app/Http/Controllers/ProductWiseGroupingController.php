<?php

namespace App\Http\Controllers;

use App\Models\ProductWiseGrouping;
use Illuminate\Http\Request;

class ProductWiseGroupingController extends Controller
{
    /**
     * Store a new product wise grouping.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'nozzle_number' => 'required|string',
            'tank_id' => 'required|exists:tanks,id',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $grouping = ProductWiseGrouping::create($validated);

        return response()->json([
            'message' => 'Product Wise Grouping created successfully.',
            'data' => $grouping
        ], 201);
    }

    /**
     * Get all product wise groupings.
     */
    public function index()
    {
        $groupings = ProductWiseGrouping::with(['machine', 'tank'])->get();

        return response()->json([
            'message' => 'Product Wise Groupings retrieved successfully.',
            'data' => $groupings
        ]);
    }
}
