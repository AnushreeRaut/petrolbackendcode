<?php

namespace App\Http\Controllers;

use App\Models\FuelSalesDetail;
use App\Models\PetrolInvoiceFeeding;
use App\Models\Tank;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariationController extends Controller
{

    public function getByDate(Request $request)
    {
        $date = $request->date;

        $variations = Variation::whereDate('created_at', $date)->get();

        return response()->json(['variations' => $variations]);
    }

    public function index()
    {
        return Variation::with('fuelSalesDetail')->get();
    }

    //  // Method to fetch data for Variation Table
    //  public function fetchVariationData()
    //  {
    //      // Fetch the tank data
    //      $tanks = Tank::all();

    //      // Fetch the fuel sales data
    //      $fuelSalesDetails = FuelSalesDetail::all();

    //      $petrolInvoiceFeeding = PetrolInvoiceFeeding::all();

    //      // Fetch the variation data with relations to tank and fuelSalesDetail
    //      $variations = Variation::with(['tank', 'fuelSalesDetail','petrolInvoiceFeeding'])->get();

    //      // Return data as JSON
    //      return response()->json([
    //          'tanks' => $tanks,
    //          'fuel_sales_details' => $fuelSalesDetails,
    //          'petrol_invoice_feeding' => $petrolInvoiceFeeding,
    //          'variations' => $variations
    //      ]);
    //  }
    // public function fetchVariationData()
    // {
    //     $tanks = Tank::all();
    //     $fuelSalesDetails = FuelSalesDetail::all();
    //     $petrolInvoiceFeeding = PetrolInvoiceFeeding::all();

    //     // Fetch total t_variation grouped by tank_id
    //     $totalTVariation = DB::table('variations')
    //         ->select('tank_id', DB::raw('SUM(t_variation) as total_t_variation'))
    //         ->groupBy('tank_id')
    //         ->get()
    //         ->keyBy('tank_id'); // Make it easy to access by tank_id

    //     return response()->json([
    //         'tanks' => $tanks,
    //         'fuel_sales_details' => $fuelSalesDetails,
    //         'petrol_invoice_feeding' => $petrolInvoiceFeeding,
    //         'total_t_variation' => $totalTVariation, // Send total T. Variation for each product
    //     ]);
    // }

    public function fetchVariationData(Request $request)
    {
        $selectedDate = $request->input('date'); // e.g., 2025-01-01

        $tanks = Tank::all();

        // Use the custom 'date' field
        $fuelSalesDetails = FuelSalesDetail::whereDate('date', $selectedDate)->get();
        $petrolInvoiceFeeding = PetrolInvoiceFeeding::whereDate('date', $selectedDate)->get();

        $totalTVariation = DB::table('variations')
            ->select('tank_id', DB::raw('SUM(t_variation) as total_t_variation'))
            ->whereDate('date', $selectedDate)
            ->groupBy('tank_id')
            ->get()
            ->keyBy('tank_id');

        return response()->json([
            'tanks' => $tanks,
            'fuel_sales_details' => $fuelSalesDetails,
            'petrol_invoice_feeding' => $petrolInvoiceFeeding,
            'total_t_variation' => $totalTVariation,
        ]);
    }


    // public function fetchVariationData()
    // {
    //     // Fetch all required data
    //     $tanks = Tank::all();
    //     $fuelSalesDetails = FuelSalesDetail::all();
    //     $petrolInvoiceFeeding = PetrolInvoiceFeeding::all();

    //     // Fetch variations with relations
    //     $variations = Variation::with(['tank', 'fuelSalesDetail', 'petrolInvoiceFeeding'])->get();

    //     // Return data with kl_qty included from petrolInvoiceFeeding
    //     return response()->json([
    //         'tanks' => $tanks,
    //         'fuel_sales_details' => $fuelSalesDetails,
    //         'petrol_invoice_feeding' => $petrolInvoiceFeeding,
    //         'variations' => $variations
    //     ]);
    // }
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'variations' => 'required|array',
    //         'variations.*.tank_id' => 'required|integer|exists:tanks,id',
    //         'variations.*.fuel_sales_details_id' => 'required|integer|exists:fuel_sales_details,id',
    //         'variations.*.petrol_invoice_feeding_id' => 'required|integer|exists:petrol_invoice_feeding,id',
    //         'variations.*.open_stk' => 'required|numeric',
    //         'variations.*.purchase' => 'nullable|numeric',
    //         'variations.*.total_stk' => 'required|numeric',
    //         'variations.*.a_sale' => 'required|numeric',
    //         'variations.*.bal_stk' => 'required|numeric',
    //         'variations.*.actual_bal_stk' => 'required|numeric', // Ensure this is included
    //         'variations.*.variation' => 'required|numeric',
    //         'variations.*.t_variation' => 'required|numeric',
    //         'variations.*.added_by' => 'required|integer',
    //     ]);

    //     Variation::insert($validated['variations']);

    //     return response()->json(['message' => 'Variations saved successfully!'], 201);
    // }

    // public function store(Request $request)
    // {
    //     // Extract variations from the request
    //     $variations = $request->input('variations', []);

    //     // Filter out variations that are missing required foreign keys
    //     $validVariations = array_filter($variations, function ($variation) {
    //         return isset($variation['tank_id'], $variation['fuel_sales_details_id'], $variation['petrol_invoice_feeding_id']);
    //     });

    //     // If no valid variations remain, return an error response
    //     if (empty($validVariations)) {
    //         return response()->json(['message' => 'No valid variations to store.'], 400);
    //     }

    //     // Validate only the filtered variations
    //     $validatedData = validator(['variations' => $validVariations], [
    //         'variations' => 'required|array',
    //         'variations.*.tank_id' => 'required|integer|exists:tanks,id',
    //         'variations.*.fuel_sales_details_id' => 'required|integer|exists:fuel_sales_details,id',
    //         'variations.*.petrol_invoice_feeding_id' => 'required|integer|exists:petrol_invoice_feeding,id',
    //         'variations.*.open_stk' => 'required|numeric',
    //         'variations.*.purchase' => 'nullable|numeric',
    //         'variations.*.total_stk' => 'required|numeric',
    //         'variations.*.a_sale' => 'required|numeric',
    //         'variations.*.bal_stk' => 'required|numeric',
    //         'variations.*.actual_bal_stk' => 'required|numeric',
    //         'variations.*.variation' => 'required|numeric',
    //         'variations.*.t_variation' => 'required|numeric',
    //         'variations.*.added_by' => 'required|integer',
    //     ])->validate();

    //     // Insert the validated variations
    //     Variation::insert($validatedData['variations']);

    //     return response()->json(['message' => 'Variations saved successfully!'], 201);
    // }
    public function store(Request $request)
    {
        $variations = $request->input('variations', []);
        $selectedDate = $request->input('date'); // ← Get selected date from request

        $validVariations = array_filter($variations, function ($variation) {
            return isset($variation['tank_id'], $variation['fuel_sales_details_id'], $variation['petrol_invoice_feeding_id']);
        });

        if (empty($validVariations)) {
            return response()->json(['message' => 'No valid variations to store.'], 400);
        }

        $validatedData = validator(['variations' => $validVariations], [
            'variations' => 'required|array',
            'variations.*.tank_id' => 'required|integer|exists:tanks,id',
            'variations.*.fuel_sales_details_id' => 'required|integer|exists:fuel_sales_details,id',
            'variations.*.petrol_invoice_feeding_id' => 'required|integer|exists:petrol_invoice_feeding,id',
            'variations.*.open_stk' => 'required|numeric',
            'variations.*.purchase' => 'nullable|numeric',
            'variations.*.total_stk' => 'required|numeric',
            'variations.*.a_sale' => 'required|numeric',
            'variations.*.bal_stk' => 'required|numeric',
            'variations.*.actual_bal_stk' => 'required|numeric',
            'variations.*.variation' => 'required|numeric',
            'variations.*.t_variation' => 'required|numeric',
            'variations.*.added_by' => 'required|integer',
        ])->validate();

        $timestamp = Carbon::now();
        $variationsWithTimestamps = array_map(function ($variation) use ($timestamp, $selectedDate) {
            return array_merge($variation, [
                'date' => $selectedDate, // ← Add selected date
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }, $validatedData['variations']);

        Variation::insert($variationsWithTimestamps);

        return response()->json(['message' => 'Variations saved successfully!'], 201);
    }
    public function getVariationsByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date');
        $variations = Variation::whereDate('date', $date)->get();

        return response()->json(['variations' => $variations]);
    }

    // public function getVariationsByDate(Request $request)
    // {
    //     $request->validate([
    //         'date' => 'required|date',
    //     ]);

    //     $date = $request->input('date');

    //     // Fetch variations for the specified date
    //     $variations = Variation::whereDate('date', $date)->get();

    //     return response()->json(['variations' => $variations]);
    // }

    // public function store(Request $request)
    // {
    //     // Extract variations from the request
    //     $variations = $request->input('variations', []);

    //     // Filter out variations that are missing required foreign keys
    //     $validVariations = array_filter($variations, function ($variation) {
    //         return isset($variation['tank_id'], $variation['fuel_sales_details_id'], $variation['petrol_invoice_feeding_id']);
    //     });

    //     // If no valid variations remain, return an error response
    //     if (empty($validVariations)) {
    //         return response()->json(['message' => 'No valid variations to store.'], 400);
    //     }

    //     // Validate only the filtered variations
    //     $validatedData = validator(['variations' => $validVariations], [
    //         'variations' => 'required|array',
    //         'variations.*.tank_id' => 'required|integer|exists:tanks,id',
    //         'variations.*.fuel_sales_details_id' => 'required|integer|exists:fuel_sales_details,id',
    //         'variations.*.petrol_invoice_feeding_id' => 'required|integer|exists:petrol_invoice_feeding,id',
    //         'variations.*.open_stk' => 'required|numeric',
    //         'variations.*.purchase' => 'nullable|numeric',
    //         'variations.*.total_stk' => 'required|numeric',
    //         'variations.*.a_sale' => 'required|numeric',
    //         'variations.*.bal_stk' => 'required|numeric',
    //         'variations.*.actual_bal_stk' => 'required|numeric',
    //         'variations.*.variation' => 'required|numeric',
    //         'variations.*.t_variation' => 'required|numeric',
    //         'variations.*.added_by' => 'required|integer',
    //     ])->validate();

    //     // Add timestamps to each variation
    //     $timestamp = Carbon::now();
    //     $variationsWithTimestamps = array_map(function ($variation) use ($timestamp) {
    //         return array_merge($variation, [
    //             'created_at' => $timestamp,
    //             'updated_at' => $timestamp,
    //         ]);
    //     }, $validatedData['variations']);

    //     // Insert the validated variations with timestamps
    //     Variation::insert($variationsWithTimestamps);

    //     return response()->json(['message' => 'Variations saved successfully!'], 201);
    // }

}
