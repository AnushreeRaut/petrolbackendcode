<?php

namespace App\Http\Controllers;

use App\Models\FuelSalesDetail;
use App\Models\TankFuleSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Import Str helper

class FuelSalesDetailController extends Controller
{


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fuelSalesDetails.*.tank_id' => 'required|integer|exists:tanks,id',
            'fuelSalesDetails.*.machine_id' => 'required|integer|exists:machines,id',
            'fuelSalesDetails.*.nozzle_name' => 'required|string',
            'fuelSalesDetails.*.opening' => 'required|numeric',
            'fuelSalesDetails.*.closing' => 'required|numeric',
            'fuelSalesDetails.*.sale' => 'required|numeric',
            'fuelSalesDetails.*.testing' => 'required|numeric',
            'fuelSalesDetails.*.a_sale' => 'required|numeric',
            'tankFuelSales' => 'required|array',
            'tankFuelSales.*.tank_id' => 'required|integer|exists:tanks,id',
            'tankFuelSales.*.rate' => 'required|numeric',
            'tankFuelSales.*.total_sales' => 'required|numeric',
            'tankFuelSales.*.total_a_sales' => 'required|numeric',
            'tankFuelSales.*.total_testing' => 'required|numeric',
            'tankFuelSales.*.total_amount' => 'required|numeric',
            'date' => 'required|date', // Validate the date field
        ]);

        $date = $validatedData['date']; // Get the date from the request

        try {
            DB::transaction(function () use ($validatedData, $date) {
                // Insert fuel sales details
                foreach ($validatedData['fuelSalesDetails'] as $detail) {
                    DB::table('fuel_sales_details')->insert([
                        'tank_id' => $detail['tank_id'],
                        'machine_id' => $detail['machine_id'],
                        'nozzle_name' => $detail['nozzle_name'],
                        'opening' => $detail['opening'],
                        'closing' => $detail['closing'],
                        'sale' => $detail['sale'],
                        'testing' => $detail['testing'],
                        'a_sale' => $detail['a_sale'],
                        'date' => $date, // Add the date field here
                        'added_by' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Insert tank fuel sales
                foreach ($validatedData['tankFuelSales'] as $sale) {
                    DB::table('tank_fule_sales')->insert([
                        'tank_id' => $sale['tank_id'],
                        'rate' => $sale['rate'],
                        'total_sales' => $sale['total_sales'],
                        'total_a_sales' => $sale['total_a_sales'],
                        'total_testing' => $sale['total_testing'],
                        'total_amount' => $sale['total_amount'],
                        'date' => $date, // Add the date field here
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            return response()->json(['message' => 'Data saved successfully.'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error saving fuel sales data: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save data.'], 500);
        }
    }


    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'fuelSalesDetails.*.tank_id' => 'required|integer|exists:tanks,id',
    //         'fuelSalesDetails.*.nozzle_name' => 'required|string',
    //         'fuelSalesDetails.*.opening' => 'required|numeric',
    //         'fuelSalesDetails.*.closing' => 'required|numeric',
    //         'fuelSalesDetails.*.sale' => 'required|numeric',
    //         'fuelSalesDetails.*.testing' => 'required|numeric',
    //         'fuelSalesDetails.*.a_sale' => 'required|numeric',
    //         'tankFuelSales' => 'required|array',
    //         'tankFuelSales.*.tank_id' => 'required|integer|exists:tanks,id',
    //         'tankFuelSales.*.rate' => 'required|numeric',
    //         'tankFuelSales.*.total_sales' => 'required|numeric',
    //         'tankFuelSales.*.total_a_sales' => 'required|numeric',
    //         'tankFuelSales.*.total_testing' => 'required|numeric',
    //         'tankFuelSales.*.total_amount' => 'required|numeric',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($validatedData) {
    //             // Insert fuel sales details
    //             foreach ($validatedData['fuelSalesDetails'] as $detail) {
    //                 DB::table('fuel_sales_details')->insert([
    //                     'tank_id' => $detail['tank_id'],
    //                     'nozzle_name' => $detail['nozzle_name'],
    //                     'opening' => $detail['opening'],
    //                     'closing' => $detail['closing'],
    //                     'sale' => $detail['sale'],
    //                     'testing' => $detail['testing'],
    //                     'a_sale' => $detail['a_sale'],
    //                     'added_by' => auth()->id(),
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }

    //             // Insert tank fuel sales
    //             foreach ($validatedData['tankFuelSales'] as $sale) {
    //                 DB::table('tank_fule_sales')->insert([
    //                     'tank_id' => $sale['tank_id'],
    //                     'rate' => $sale['rate'],
    //                     'total_sales' => $sale['total_sales'],
    //                     'total_a_sales' => $sale['total_a_sales'],
    //                     'total_testing' => $sale['total_testing'],
    //                     'total_amount' => $sale['total_amount'],
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);
    //             }
    //         });

    //         return response()->json(['message' => 'Data saved successfully.'], 200);
    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Error saving fuel sales data: ', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Failed to save data. ' . $e->getMessage()], 500);
    //     }
    // }


    // public function getRates(Request $request)
    // {
    //     $date = $request->query('date', now()->toDateString());  // Get date from query parameters, default to today's date

    //     // Retrieve the rates from the 'day_start' table for the given date
    //     $rates = DB::table('day_start')
    //         ->where('date', $date)
    //         ->select('ms_rate_day', 'speed_rate_day', 'hsd_rate_day', 'date')
    //         ->first();

    //     // Return the rates for the products
    //     return response()->json([
    //         'MS' => $rates->ms_rate_day,
    //         'MS(speed)' => $rates->speed_rate_day,
    //         'HSD' => $rates->hsd_rate_day,
    //     ]);
    // }
    // public function getRates(Request $request)
    // {
    //     $date = $request->query('date', Carbon::now()->toDateString()); // Default to today's date

    //     // Retrieve the rates from the 'day_start' table for the given date
    //     $rates = DB::table('day_start')
    //         ->whereDate('date', $date) // Ensures proper date comparison
    //         ->select('ms_rate_day', 'speed_rate_day', 'hsd_rate_day', 'date')
    //         ->first();

    //     // If no rates found, return a response indicating no data
    //     if (!$rates) {
    //         return response()->json([
    //             'message' => 'No rates available for the selected date.',
    //             'MS' => null,
    //             'MS(speed)' => null,
    //             'HSD' => null,
    //         ], 404);
    //     }

    //     // Return the rates for the products
    //     return response()->json([
    //         'MS' => $rates->ms_rate_day,
    //         'MS(speed)' => $rates->speed_rate_day,
    //         'HSD' => $rates->hsd_rate_day,
    //     ]);
    // }

    public function getRates(Request $request)
    {
        $date = $request->query('date', Carbon::now()->toDateString()); // Default to today's date

        $rates = DB::table('day_start')
            ->whereDate('date', $date)
            ->select('ms_rate_day', 'speed_rate_day', 'hsd_rate_day')
            ->first();

        if (!$rates) {
            return response()->json(['message' => 'No rates available for the selected date.'], 404);
        }

        return response()->json([
            'MS' => $rates->ms_rate_day,
            'MS(speed)' => $rates->speed_rate_day,
            'HSD' => $rates->hsd_rate_day,
        ]);
    }


    // public function getFuelSalesData(Request $request)
    // {
    //     $date = $request->query('date', now()->toDateString());

    //     $fuelSales = FuelSalesDetail::whereDate('created_at', $date)->get();

    //     return response()->json($fuelSales);
    // }

    // public function getFuelSalesData()
    // {
    //     $fuelSales = FuelSalesDetail::whereDate('created_at', now()->toDateString())->get();

    //     return response()->json($fuelSales);
    // }
    public function getFuelSalesData(Request $request)
    {
        $date = $request->input('date');
        $fuelSales = FuelSalesDetail::where('date', $date)->get();
        return response()->json($fuelSales);
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'fuelSalesDetails' => 'required|array',
            'fuelSalesDetails.*.id' => 'required|integer|exists:fuel_sales_details,id',
            'fuelSalesDetails.*.tank_id' => 'required|integer|exists:tanks,id',
            'fuelSalesDetails.*.nozzle_name' => 'required|string',
            'fuelSalesDetails.*.opening' => 'required|numeric',
            'fuelSalesDetails.*.closing' => 'required|numeric',
            'fuelSalesDetails.*.sale' => 'required|numeric',
            'fuelSalesDetails.*.testing' => 'required|numeric',
            'fuelSalesDetails.*.a_sale' => 'required|numeric',

            'tankFuelSales' => 'required|array',
            'tankFuelSales.*.tank_id' => 'required|integer|exists:tanks,id',
            'tankFuelSales.*.rate' => 'required|numeric',
            'tankFuelSales.*.total_sales' => 'required|numeric',
            'tankFuelSales.*.total_a_sales' => 'required|numeric',
            'tankFuelSales.*.total_testing' => 'required|numeric',
            'tankFuelSales.*.total_amount' => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($validatedData) {
                // Update fuel sales details
                foreach ($validatedData['fuelSalesDetails'] as $detail) {
                    DB::table('fuel_sales_details')
                        ->where('id', $detail['id'])
                        ->update([
                            'tank_id' => $detail['tank_id'],
                            'nozzle_name' => $detail['nozzle_name'],
                            'opening' => $detail['opening'],
                            'closing' => $detail['closing'],
                            'sale' => $detail['sale'],
                            'testing' => $detail['testing'],
                            'a_sale' => $detail['a_sale'],
                            'updated_at' => now(),
                        ]);
                }

                // Update tank fuel sales
                foreach ($validatedData['tankFuelSales'] as $sale) {
                    DB::table('tank_fuel_sales')
                        ->updateOrInsert(
                            ['tank_id' => $sale['tank_id']],
                            [
                                'rate' => $sale['rate'],
                                'total_sales' => $sale['total_sales'],
                                'total_a_sales' => $sale['total_a_sales'],
                                'total_testing' => $sale['total_testing'],
                                'total_amount' => $sale['total_amount'],
                                'updated_at' => now(),
                            ]
                        );
                }
            });

            return response()->json(['message' => 'Data updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // public function store(Request $request)
    // {
    //     $data = $request->all(); // Expecting array of objects

    //     try {
    //         foreach ($data as $entry) {
    //             // Insert into `fuel_sales_details`
    //             $fuelSaleDetail = FuelSalesDetail::create([
    //                 'tank_id' => $entry['tank_number'],
    //                 'nozzle_name' => $entry['nozzle_number'],
    //                 'opening' => $entry['opening_reading'],
    //                 'closing' => $entry['closing_reading'],
    //                 'sale' => $entry['sale'],
    //                 'testing' => $entry['testing'],
    //                 'a_sale' => $entry['a_sale'],
    //                 'added_by' => auth()->id(), // Use current user ID
    //             ]);

    //             // Insert into `tank_fule_sales` (if applicable)
    //             TankFuleSale::create([
    //                 'tank_id' => $entry['tank_number'],
    //                 'rate' => $entry['rate'],
    //                 'total_sales' => $entry['sale'],
    //                 'total_a_sales' => $entry['a_sale'],
    //                 'total_testing' => $entry['testing'],
    //                 'total_amount' => $entry['amount'],
    //             ]);
    //         }

    //         return response()->json(['message' => 'Data saved successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error saving data', 'error' => $e->getMessage()], 500);
    //     }
    // }

}
