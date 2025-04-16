<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tank;
use Illuminate\Support\Facades\DB;

class TankController extends Controller
{
    public function index()
    {
        $tanks = Tank::all();

        if ($tanks->isEmpty()) {
            return response()->json(['message' => 'No tanks found'], 404);
        }

        return response()->json(['message' => 'Tanks fetched successfully', 'data' => $tanks], 200);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'tank_number' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'no_of_nozzles' => 'required|integer',
            'capacity' => 'required|numeric',
            'opening_reading' => 'nullable|numeric',  // Make it nullable as it's not required
        ]);

        // If opening_reading is not provided, set it to 0
        $openingReading = $validated['opening_reading'] ?? 0;

        // Set added_by and updated_by to the authenticated user
        $addedBy = auth()->id(); // Assuming the user is authenticated and you are using Laravel's Auth system
        $updatedBy = $addedBy;  // If updated_by should be the same as added_by initially

        // Create a new tank record
        $tank = Tank::create(array_merge($validated, [
            'added_by' => $addedBy,
            'updated_by' => $updatedBy,
            'opening_reading' => $openingReading
        ]));

        return response()->json(['message' => 'Tank created successfully', 'data' => $tank], 201);
    }

    public function show($id)
    {
        $tank = Tank::find($id);

        if (!$tank) {
            return response()->json(['message' => 'Tank not found'], 404);
        }

        return response()->json(['message' => 'Tank fetched successfully', 'data' => $tank], 200);
    }

    public function updateOpeningStock(Request $request, $id)
    {
        $validated = $request->validate([
            'opening_reading' => 'required|numeric|min:0',
        ]);

        $tank = Tank::find($id);

        if (!$tank) {
            return response()->json(['error' => 'Tank not found'], 404);
        }

        $tank->update(['opening_reading' => $validated['opening_reading']]);

        return response()->json(['message' => 'Opening stock updated successfully'], 200);
    }


    public function update(Request $request, $id)
    {
        $tank = Tank::find($id);

        if (!$tank) {
            return response()->json(['message' => 'Tank not found'], 404);
        }

        $validated = $request->validate([
            'tank_number' => 'sometimes|required|string|max:255',
            'product' => 'sometimes|required|string|max:255',
            'no_of_nozzles' => 'sometimes|required|integer',
            'capacity' => 'sometimes|required|numeric',
            'opening_reading' => 'nullable|numeric',
            'updated_by' => 'required|integer',
        ]);

        $tank->update($validated);

        return response()->json(['message' => 'Tank updated successfully', 'data' => $tank], 200);
    }

    public function destroy($id)
    {
        $tank = Tank::find($id);

        if (!$tank) {
            return response()->json(['message' => 'Tank not found'], 404);
        }

        $tank->delete();

        return response()->json(['message' => 'Tank deleted successfully'], 200);
    }


    // In your Laravel Controller
    public function getAllTankProducts()
    {
        $products = Tank::all(['id', 'product']); // Adjust fields as necessary
        return response()->json($products);
    }


    public function toggleStatus($id)
{
    $tank = Tank::find($id);

    if (!$tank) {
        return response()->json(['message' => 'Tank not found'], 404);
    }

    $tank->status = !$tank->status; // Toggle status
    $tank->save();

    return response()->json(['message' => 'Status updated successfully', 'data' => $tank], 200);
}


//     public function getTanks()
// {
//     $tanks = DB::table('tanks')->select('id', 'tank_number')->get();
//     return response()->json($tanks);
// }

public function getTanks()
{
    $tanks = DB::table('tanks')->select('id', 'tank_number', 'product')->get();
    return response()->json($tanks);
}

}
