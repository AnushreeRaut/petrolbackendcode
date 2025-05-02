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

        $variations = Variation::whereDate('date', $date)->get();

        return response()->json(['variations' => $variations]);
    }

    public function index()
    {
        return Variation::with('fuelSalesDetail')->get();
    }



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





}
