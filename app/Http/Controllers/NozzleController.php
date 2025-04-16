<?php

namespace App\Http\Controllers;

use App\Models\Nozzle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // Correct namespace for Request
use Illuminate\Support\Facades\Validator;

class NozzleController extends Controller
{
    public function getNozzlesGroupedByProduct(): JsonResponse
    {
        $data = Nozzle::with(['tank', 'machine'])
            ->whereHas('machine', function ($query) {
                $query->where('is_active', true); // Filter nozzles where the associated machine is active
            })
            ->get()
            ->groupBy('tank.product') // Group nozzles by product
            ->map(function ($nozzles, $product) {
                return [
                    'product' => $product,
                    'nozzles' => $nozzles->map(function ($nozzle) {
                        return [
                            'nozzle_number' => $nozzle->nozzle_number,
                            'opening_reading' => $nozzle->opening_reading,
                            'machine' => $nozzle->machine->dispensing_unit_no ?? 'N/A',
                        ];
                    }),
                ];
            })
            ->values();

        return response()->json($data);
    }


    // public function getNozzlesGroupedByProductwise(): JsonResponse
    // {
    //     $data = Nozzle::with(['tank', 'machine'])
    //         ->whereHas('machine', function ($query) {
    //             $query->where('is_active', true); // Filter only active machines
    //         })
    //         ->get()
    //         ->groupBy('tank.product') // Group nozzles by product
    //         ->map(function ($nozzles, $product) {
    //             return [
    //                 'product' => $product,
    //                 'nozzles' => $nozzles->map(function ($nozzle) {
    //                     return [
    //                         'nozzle_number' => $nozzle->nozzle_number,
    //                         'opening_reading' => $nozzle->opening_reading,
    //                         'machine' => $nozzle->machine->dispensing_unit_no ?? 'N/A',
    //                     ];
    //                 })->values(),
    //             ];
    //         })
    //         ->values();

    //     // Define sorting order for products
    //     $productOrder = ['MS', 'MS(speed)', 'HSD'];

    //     // Sort the products based on the defined order
    //     $sortedData = $data->sortBy(function ($item) use ($productOrder) {
    //         $index = array_search($item['product'], $productOrder);
    //         return $index !== false ? $index : count($productOrder);
    //     })->values();

    //     return response()->json($sortedData);
    // }
    public function getNozzlesGroupedByProductwise(): JsonResponse
    {
        $data = Nozzle::with(['tank', 'machine'])
            ->whereHas('machine', function ($query) {
                $query->where('is_active', true); // Filter only active machines
            })
            ->get()
            ->sortBy('tank.tank_number') // Sort by tank number
            ->groupBy('tank.tank_number') // Group by tank number
            ->map(function ($nozzles, $tankNumber) {
                return [
                    'tank_number' => $tankNumber,
                    'product' => $nozzles->first()->tank->product ?? 'N/A',
                    'nozzles' => $nozzles->map(function ($nozzle) {
                        return [
                            'nozzle_number' => $nozzle->nozzle_number,
                            'opening_reading' => $nozzle->opening_reading,
                            'machine' => $nozzle->machine->dispensing_unit_no ?? 'N/A',
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($data);
    }


    public function getNozzles()
    {
        $nozzles = Nozzle::with(['machine', 'tank'])->whereHas('machine', function ($query) {
            $query->where('is_active', true); // Filter nozzles where the associated machine is active
        })->get(); // Eager load machine and tank relationships
        return response()->json($nozzles);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nozzle_stamping_date' => 'nullable|date',
            'nozzle_next_due_date' => 'nullable|date',
        ]);

        $nozzle = Nozzle::findOrFail($id);
        $nozzle->update($validated);

        return response()->json($nozzle);
    }

}
