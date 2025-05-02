<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Nozzle;
use App\Models\Tank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MachineController extends Controller
{
    // new
    public function index()
    {
        $machines = Machine::with(['nozzles', 'tanks'])
            ->orderBy('sr_no', 'asc')
            ->get();

        $response = $machines->map(function ($machine) {
            return [
                'id' => $machine->id, // Add this to fix the issue
                'dispensing_unit_no' => $machine->dispensing_unit_no,
                'make' => $machine->make,
                'serial_no' => $machine->serial_no,
                'sr_no' => $machine->sr_no,
                'connected_tanks' => $machine->tanks->map(function ($tank) {
                    return [
                        'tank_number' => $tank->tank_number,
                        'product' => $tank->product,
                    ];
                }),
                'nozzles' => $machine->nozzles->map(function ($nozzle) use ($machine) {
                    $product = $machine->tanks->where('id', $nozzle->tank_id)->first()->product ?? null;

                    return [
                        'nozzle_number' => $nozzle->nozzle_number,
                        'side1' => $nozzle->side1 ? $product : null,
                        'side2' => $nozzle->side2 ? $product : null,
                    ];
                }),
                'is_active' => $machine->is_active, // Ensure this field is included
            ];
        });

        return response()->json($response);
    }


    public function showtodays()
    {
        $machines = Machine::with(['nozzles', 'tanks'])
            ->whereDate('created_at', Carbon::today()) // Filter only today's created machines
            ->orderBy('sr_no', 'asc')
            ->get();

        $response = $machines->map(function ($machine) {
            return [
                'id' => $machine->id,
                'dispensing_unit_no' => $machine->dispensing_unit_no,
                'make' => $machine->make,
                'serial_no' => $machine->serial_no,
                'sr_no' => $machine->sr_no,
                'connected_tanks' => $machine->tanks->map(function ($tank) {
                    return [
                        'tank_number' => $tank->tank_number,
                        'product' => $tank->product,
                    ];
                }),
                'nozzles' => $machine->nozzles->map(function ($nozzle) use ($machine) {
                    $product = $machine->tanks->where('id', $nozzle->tank_id)->first()->product ?? null;

                    return [
                        'nozzle_number' => $nozzle->nozzle_number,
                        'side1' => $nozzle->side1 ? $product : null,
                        'side2' => $nozzle->side2 ? $product : null,
                    ];
                }),
                'is_active' => $machine->is_active,
            ];
        });

        return response()->json($response);
    }

    public function store(Request $request)
    {
        // Validate the machine form data
        $request->validate([
            'sr_no' => 'required|integer',
            'dispensing_unit_no' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'serial_no' => 'required|string|max:255',
            'connected_tanks' => 'required|array', // Multi-select tanks for the machine
            'connected_tanks.*' => 'exists:tanks,id', // Validate each selected tank ID
            'number_of_nozzles' => 'required|integer|min:1',
            // 'opening_reading' => 'required|numeric',
            'nozzles' => 'required|array', // The nozzles data
            'stamping_date' => 'nullable|date', // Optional stamping date
            'next_due_date' => 'nullable|date|after_or_equal:stamping_date', // Optional next due date
        ]);

        // Create the machine
        $machine = Machine::create([
            'sr_no' => $request->sr_no,
            'dispensing_unit_no' => $request->dispensing_unit_no,
            'make' => $request->make,
            'serial_no' => $request->serial_no,
            'connected_tanks' => implode(',', $request->connected_tanks), // Store as a comma-separated string
            'tank_id' => $request->tank_id, // Machine's tank
            'number_of_nozzles' => $request->number_of_nozzles,
            // 'opening_reading' => $request->opening_reading,
            'stamping_date' => $request->stamping_date,
            'next_due_date' => $request->next_due_date,
            'added_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        // Attach connected tanks to the machine using the pivot table
        $machine->tanks()->sync($request->connected_tanks);

        // Handle the nozzles data
        $this->storeNozzles($request, $machine);

        return response()->json([
            'message' => 'Machine and nozzles saved successfully',
            'machine' => $machine,
        ], 201);
    }
    /**
     * Save the nozzles associated with the machine.
     */
    private function storeNozzles(Request $request, Machine $machine)
    {
        foreach ($request->nozzles as $nozzleData) {
            Nozzle::create([
                'machine_id' => $machine->id,
                'tank_id' => $nozzleData['tank_id'], // This allows each nozzle to have its own tank_id
                'nozzle_number' => $nozzleData['nozzle_number'],
                'side1' => $nozzleData['side1'],
                'side2' => $nozzleData['side2'],
                'nozzle_stamping_date' => $nozzleData['nozzle_stamping_date'],
                'nozzle_next_due_date' => $nozzleData['nozzle_next_due_date'],
            ]);
        }
    }

    /**
     * Update a machine and its associated nozzles.
     */
    // public function update(Request $request, $id)
    // {
    //     $machine = Machine::findOrFail($id);

    //     // Validate the machine form data
    //     $request->validate([
    //         'dispensing_unit_no' => 'required|string|max:255',
    //         'make' => 'required|string|max:255',
    //         'serial_no' => 'required|string|max:255',
    //         'connected_tanks' => 'required|string|max:255',
    //         'tank_id' => 'required|exists:tanks,id',
    //         'number_of_nozzles' => 'required|integer|min:1',
    //         'opening_reading' => 'required|numeric',
    //         'nozzles' => 'required|array',
    //     ]);

    //     // Update the machine data
    //     $machine->update([
    //         'dispensing_unit_no' => $request->dispensing_unit_no,
    //         'make' => $request->make,
    //         'serial_no' => $request->serial_no,
    //         'connected_tanks' => $request->connected_tanks,
    //         'tank_id' => $request->tank_id,
    //         'number_of_nozzles' => $request->number_of_nozzles,
    //         'opening_reading' => $request->opening_reading,
    //         'updated_by' => Auth::id(),
    //     ]);

    //     // Update the nozzles data
    //     $this->updateNozzles($request, $machine);

    //     return response()->json([
    //         'message' => 'Machine and nozzles updated successfully',
    //         'machine' => $machine,
    //     ]);
    // }

    /**
     * Update the nozzles associated with the machine.
     */
    // private function updateNozzles(Request $request, Machine $machine)
    // {
    //     // Delete existing nozzles
    //     $machine->nozzles()->delete();

    //     // Add the new nozzles
    //     $this->storeNozzles($request, $machine);
    // }

    /**
     * Delete a machine and its associated nozzles.
     */
    public function destroy($id)
    {
        $machine = Machine::findOrFail($id);

        // Delete the machine and its nozzles
        $machine->nozzles()->delete();
        $machine->delete();

        return response()->json(['message' => 'Machine and nozzles deleted successfully']);
    }

    public function toggleStatus($id)
    {
        $machine = Machine::find($id);

        if (!$machine) {
            return response()->json(['error' => 'Machine not found'], 404);
        }

        $machine->is_active = !$machine->is_active; // Toggle status
        $machine->save();

        return response()->json(['is_active' => $machine->is_active], 200);
    }



    // update
    /**
     * Display the specified machine for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    // public function show($id)
    // {
    //     $machine = Machine::with(['nozzles', 'tanks'])->findOrFail($id);

    //     // Format the data for the React component
    //     $response = [
    //         'id' => $machine->id,
    //         'sr_no' => $machine->sr_no,
    //         'dispensing_unit_no' => $machine->dispensing_unit_no,
    //         'make' => $machine->make,
    //         'serial_no' => $machine->serial_no,
    //         'connected_tanks' => $machine->tanks->pluck('id')->toArray(), // Return an array of tank IDs
    //         'tank_id' => $machine->tank_id,
    //         'number_of_nozzles' => $machine->number_of_nozzles,
    //         'opening_reading' => $machine->opening_reading,
    //         'stamping_date' => $machine->stamping_date,
    //         'next_due_date' => $machine->next_due_date,
    //         'nozzles' => $machine->nozzles->map(function ($nozzle) {
    //             return [
    //                 'id' => $nozzle->id,
    //                 'nozzle_number' => $nozzle->nozzle_number,
    //                 'type' => $nozzle->type,
    //                 'tank_id' => $nozzle->tank_id,
    //                 'nozzle_stamping_date' => $nozzle->nozzle_stamping_date,
    //                 'nozzle_next_due_date' => $nozzle->nozzle_next_due_date,
    //                 'side1' => (bool) $nozzle->side1,
    //                 'side2' => (bool) $nozzle->side2,
    //             ];
    //         })->toArray(), // Ensure nozzles are always an array, even if empty
    //     ];

    //     return response()->json($response);
    // }
    // public function show($id)
    // {
    //     $machine = Machine::with(['tanks'])->findOrFail($id);

    //     // Format the data for the React component
    //     $response = [
    //         'id' => $machine->id,
    //         'sr_no' => $machine->sr_no,
    //         'dispensing_unit_no' => $machine->dispensing_unit_no,
    //         'make' => $machine->make,
    //         'serial_no' => $machine->serial_no,
    //         'connected_tanks' => $machine->tanks->pluck('id')->toArray(), // Return an array of tank IDs
    //         'tank_id' => $machine->tank_id,
    //         'number_of_nozzles' => $machine->number_of_nozzles,
    //         'opening_reading' => $machine->opening_reading,
    //         'stamping_date' => $machine->stamping_date,
    //         'next_due_date' => $machine->next_due_date,
    //         'nozzles' => $machine->nozzles->map(function ($nozzle) {
    //             return [
    //                 'id' => $nozzle->id,
    //                 'nozzle_number' => $nozzle->nozzle_number,
    //                 'type' => $nozzle->type,
    //                 'tank_id' => $nozzle->tank_id,
    //                 'nozzle_stamping_date' => $nozzle->nozzle_stamping_date,
    //                 'nozzle_next_due_date' => $nozzle->nozzle_next_due_date,
    //                 'side1' => (bool) $nozzle->side1,
    //                 'side2' => (bool) $nozzle->side2,
    //             ];
    //         })->toArray(), // Ensure nozzles are always an array, even if empty
    //     ];

    //     return response()->json($response);
    // }

    public function show($id)
    {
        $machine = Machine::with(['tanks', 'nozzles'])->findOrFail($id);

        $response = [
            'id' => $machine->id,
            'sr_no' => $machine->sr_no,
            'dispensing_unit_no' => $machine->dispensing_unit_no,
            'make' => $machine->make,
            'serial_no' => $machine->serial_no,
            'connected_tanks' => $machine->tanks->map(function ($tank) {
                return [
                    'id' => $tank->id,
                    'product' => $tank->product, // Directly access the product column
                    // Add other tank properties as needed
                ];
            })->toArray(),
            'tank_id' => $machine->tank_id,
            'number_of_nozzles' => $machine->number_of_nozzles,
            'opening_reading' => $machine->opening_reading,
            'stamping_date' => $machine->stamping_date,
            'next_due_date' => $machine->next_due_date,
            'nozzles' => $machine->nozzles->map(function ($nozzle) {
                return [
                    'id' => $nozzle->id,
                    'nozzle_number' => $nozzle->nozzle_number,
                    'type' => $nozzle->type,
                    'tank_id' => $nozzle->tank_id,
                    'nozzle_stamping_date' => $nozzle->nozzle_stamping_date,
                    'nozzle_next_due_date' => $nozzle->nozzle_next_due_date,
                    'side1' => (bool)$nozzle->side1,
                    'side2' => (bool)$nozzle->side2,
                ];
            })->toArray(),
        ];

        return response()->json($response);
    }
    /**
     * Update the specified machine in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $machine = Machine::findOrFail($id);

        $request->validate([
            'sr_no' => 'required|integer',
            'dispensing_unit_no' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'serial_no' => 'required|string|max:255',
            'connected_tanks' => 'required|array', // Expect an array of tank IDs
            'connected_tanks.*' => 'exists:tanks,id', // Validate each tank ID
            'number_of_nozzles' => 'required|integer|min:1',
            'opening_reading' => 'nullable|numeric', // Make opening_reading nullable
            'stamping_date' => 'nullable|date', // Optional stamping date
            'next_due_date' => 'nullable|date|after_or_equal:stamping_date', // Optional next due date
            'nozzles' => 'required|array', // The nozzles data
        ]);

        $machine->update([
            'sr_no' => $request->sr_no,
            'dispensing_unit_no' => $request->dispensing_unit_no,
            'make' => $request->make,
            'serial_no' => $request->serial_no,
            'number_of_nozzles' => $request->number_of_nozzles,
            'opening_reading' => $request->input('opening_reading', null), // Use request->input with a default value
            'stamping_date' => $request->stamping_date,
            'next_due_date' => $request->next_due_date,
            'updated_by' => Auth::id(),
        ]);

        $machine->tanks()->sync($request->connected_tanks);

        $this->updateNozzles($request, $machine);

        return response()->json([
            'message' => 'Machine and nozzles updated successfully',
            'machine' => $machine,
        ]);
    }

    /**
     * Update the nozzles associated with the machine.
     */
    // private function updateNozzles(Request $request, Machine $machine)
    // {
    //     // Delete existing nozzles
    //     $machine->nozzles()->delete();

    //     // Add the new nozzles
    //     $this->storeNozzles($request, $machine);
    // }
    private function updateNozzles(Request $request, Machine $machine)
    {
        $existingNozzles = $machine->nozzles->keyBy('id');
        $incomingNozzles = collect($request->nozzles);

        foreach ($incomingNozzles as $nozzleData) {
            if (isset($nozzleData['id']) && $existingNozzles->has($nozzleData['id'])) {
                // Update existing nozzle
                $existingNozzles[$nozzleData['id']]->update([
                    'nozzle_number' => $nozzleData['nozzle_number'],
                    'type' => $nozzleData['type'],
                    'tank_id' => $nozzleData['tank_id'],
                    'nozzle_stamping_date' => $nozzleData['nozzle_stamping_date'],
                    'nozzle_next_due_date' => $nozzleData['nozzle_next_due_date'],
                    'side1' => $nozzleData['side1'],
                    'side2' => $nozzleData['side2'],
                ]);
            } else {
                // Create new nozzle
                $machine->nozzles()->create([
                    'nozzle_number' => $nozzleData['nozzle_number'],
                    'type' => $nozzleData['type'],
                    'tank_id' => $nozzleData['tank_id'],
                    'nozzle_stamping_date' => $nozzleData['nozzle_stamping_date'],
                    'nozzle_next_due_date' => $nozzleData['nozzle_next_due_date'],
                    'side1' => $nozzleData['side1'],
                    'side2' => $nozzleData['side2'],
                ]);
            }
        }

        // Delete nozzles that are no longer present
        $incomingIds = $incomingNozzles->pluck('id')->filter()->all();
        $machine->nozzles()->whereNotIn('id', $incomingIds)->delete();
    }
    public function getMachineProductDetails()
    {
        $tanks = Tank::withCount('nozzles')->get();

        $machines = Machine::where('is_active', true) // 👈 Only active machines
            ->with(['tanks', 'nozzles.tank'])
            ->get();

        return response()->json([
            'tanks' => $tanks,
            'machines' => $machines,
        ]);
    }

}
