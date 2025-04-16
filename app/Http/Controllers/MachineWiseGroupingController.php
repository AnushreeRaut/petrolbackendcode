<?php

namespace App\Http\Controllers;

use App\Models\MachineWiseGrouping;
use Illuminate\Http\Request;

class MachineWiseGroupingController extends Controller
{
    /**
     * Store a new machine wise grouping.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'nozzle_number' => 'required|string',
            'tank_id' => 'required|exists:tanks,id',
            'added_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ]);

        $grouping = MachineWiseGrouping::create($validated);

        return response()->json([
            'message' => 'Machine Wise Grouping created successfully.',
            'data' => $grouping
        ], 201);
    }

    /**
     * Get all machine wise groupings.
     */
    public function index()
    {
        $groupings = MachineWiseGrouping::with(['machine', 'tank'])->get();

        return response()->json([
            'message' => 'Machine Wise Groupings retrieved successfully.',
            'data' => $groupings
        ]);
    }
}
