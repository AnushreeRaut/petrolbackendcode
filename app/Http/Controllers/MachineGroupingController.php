<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Nozzle;
use Illuminate\Http\Request;

class MachineGroupingController extends Controller
{

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'opening_reading' => 'required|numeric',
    //     ]);

    //     $nozzle = Nozzle::findOrFail($id);
    //     $nozzle->update(['opening_reading' => $request->opening_reading]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Opening reading updated successfully.',
    //         'nozzle' => $nozzle,
    //     ]);
    // }

    public function update(Request $request, $id)
{
    $request->validate([
        'opening_reading' => 'required|numeric',
    ]);

    $nozzle = Nozzle::findOrFail($id);
    $nozzle->opening_reading = (float) $request->opening_reading;
    $nozzle->save();

    return response()->json([
        'success' => true,
        'message' => 'Opening reading updated successfully.',
        'nozzle' => $nozzle,
    ]);
}


    // public function getAllMachineDetails()
    // {
    //     $machines = Machine::with(['nozzles.tank'])->where('is_active', true)->get();
    //     return response()->json($machines->map(function ($machine) {
    //         return [
    //             'machine' => $machine->dispensing_unit_no,
    //             'nozzles' => $machine->nozzles->map(function ($nozzle) {
    //                 return [
    //                     'id' => $nozzle->id,
    //                     'tank_id' => $nozzle->tank->id, // Ensure tank_id is included
    //                     'nozzle_number' => $nozzle->nozzle_number,
    //                     'tank_number' => $nozzle->tank->tank_number,
    //                     'product' => $nozzle->tank->product,
    //                     'opening_reading' => $nozzle->opening_reading,
    //                 ];
    //             }),
    //         ];
    //     }));
    // }
    // old
    // public function getAllMachineDetails()
    // {
    //     $machines = Machine::with(['nozzles.tank'])
    //         ->where('is_active', true)
    //         ->get();

    //     return response()->json($machines->map(function ($machine) {
    //         return [
    //             'machine' => $machine->dispensing_unit_no,
    //             'machine_id' => $machine->id,
    //             'nozzles' => $machine->nozzles->map(function ($nozzle) {
    //                 return [
    //                     'id' => $nozzle->id,
    //                     'tank_id' => $nozzle->tank->id ?? null, // Ensure tank_id is included
    //                     'nozzle_number' => $nozzle->nozzle_number,
    //                     'tank_number' => $nozzle->tank->tank_number ?? 'N/A',
    //                     'product' => $nozzle->tank->product ?? 'N/A',
    //                     'opening_reading' => $nozzle->opening_reading,
    //                     'side1' => $nozzle->side1, // Ensure side1 is included
    //                     'side2' => $nozzle->side2, // Ensure side2 is included
    //                 ];
    //             }),
    //         ];
    //     }));
    // }
    // new
    public function getAllMachineDetails()
{
    $machines = Machine::with(['nozzles.tank'])
        ->where('is_active', true)
        ->get();

    return response()->json($machines->map(function ($machine) {
        return [
            'machine' => $machine->dispensing_unit_no,
            'machine_id' => $machine->id,
            'nozzles' => $machine->nozzles->map(function ($nozzle) use ($machine) {
                return [
                    'id' => $nozzle->id,
                    'machine_id' => $machine->id, // Add machine_id
                    'tank_id' => $nozzle->tank->id ?? null,
                    'nozzle_number' => $nozzle->nozzle_number,
                    'tank_number' => $nozzle->tank->tank_number ?? 'N/A',
                    'product' => $nozzle->tank->product ?? 'N/A',
                    'opening_reading' => $nozzle->opening_reading,
                    'side1' => $nozzle->side1,
                    'side2' => $nozzle->side2,
                ];
            }),
        ];
    }));
}


    // public function getAllMachineDetails()
    // {
    //     $machines = Machine::with(['nozzles.tank'])->get();

    //     return response()->json($machines->map(function ($machine) {
    //         return [
    //             'machine' => $machine->dispensing_unit_no,
    //             'nozzles' => $machine->nozzles->map(function ($nozzle) {
    //                 return [
    //                     'id' => $nozzle->id,
    //                     'nozzle_number' => $nozzle->nozzle_number,
    //                     'tank_number' => $nozzle->tank->tank_number,
    //                     'product' => $nozzle->tank->product,
    //                     'opening_reading' => $nozzle->opening_reading,
    //                 ];
    //             }),
    //         ];
    //     }));
    // }


}
