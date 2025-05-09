<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use App\Models\OilProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GodownController extends Controller
{
    public function getStockByDate(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString()); // Default to today's date if none provided

        Log::info('Received date:', ['date' => $selectedDate]);

        // Filter stock data by the selected date
        $oilProducts = OilProduct::with(['oilInvoices' => function ($query) use ($selectedDate) {
            $query->whereDate('date', $selectedDate); // Use 'date' column instead of 'created_at'
        }])
            ->whereHas('oilInvoices', function ($query) use ($selectedDate) {
                $query->whereDate('date', $selectedDate); // Filter by 'date' column
            })
            ->with(['godownStock' => function ($query) {
                $query->select('oil_product_id', 'oil_invoices_id', 'outward_retail', 'bal_stk', 'opening_stk');
            }])
            ->get();

        Log::info('Stock data being returned:', ['data' => $oilProducts]);
        return response()->json($oilProducts);
    }


    public function getTodaysStock(Request $request)
    {
        // $today = now()->toDateString();

        // $oilProducts = OilProduct::with(['oilInvoices' => function ($query) use ($today) {
        //     $query->whereDate('created_at', $today);
        // }])
        //     ->whereHas('oilInvoices', function ($query) use ($today) {
        //         $query->whereDate('created_at', $today);
        //     })
        //     ->with(['godownStock' => function ($query) { // Fetch existing stock data
        //         $query->select('oil_product_id', 'oil_invoices_id', 'outward_retail', 'bal_stk', 'opening_stk');
        //     }])
        //     ->get();

        // return response()->json($oilProducts);
        $selectedDate = $request->input('date', now()->toDateString()); // Default to today's date if none provided

        Log::info('Received date:', ['date' => $selectedDate]);

        // Filter stock data by the selected date
        $oilProducts = OilProduct::with(['oilInvoices' => function ($query) use ($selectedDate) {
            $query->whereDate('date', $selectedDate); // Use 'date' column instead of 'created_at'
        }])
            ->whereHas('oilInvoices', function ($query) use ($selectedDate) {
                $query->whereDate('date', $selectedDate); // Filter by 'date' column
            })
            ->with(['godownStock' => function ($query) {
                $query->select('oil_product_id', 'oil_invoices_id', 'outward_retail', 'bal_stk', 'opening_stk');
            }])
            ->get();

        Log::info('Stock data being returned:', ['data' => $oilProducts]);
        return response()->json($oilProducts);
    }



    // new
    // public function store(Request $request)
    // {
    //     try {
    //         // Validate request
    //         $validatedData = $request->validate([
    //             'stockData' => 'required|array',
    //             'stockData.*.oil_invoices_id' => 'required|integer',
    //             'stockData.*.oil_product_id' => 'required|integer',
    //             'stockData.*.opening_stk' => 'required|numeric',
    //             'stockData.*.t_opening_stk' => 'required|numeric',
    //             'stockData.*.outward_retail' => 'nullable|numeric',
    //             'stockData.*.bal_stk' => 'required|numeric',
    //         ]);
    //         foreach ($request->stockData as $data) {
    //             if (!\App\Models\OilInvoice::find($data['oil_invoices_id'])) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Invalid oil invoice ID: ' . $data['oil_invoices_id'],
    //                 ], 422);
    //             }
    //         }

    //         foreach ($validatedData['stockData'] as $data) {
    //             Godown::updateOrCreate(
    //                 [
    //                     'oil_product_id' => $data['oil_product_id'],
    //                     'oil_invoices_id' => $data['oil_invoices_id'],
    //                 ],
    //                 [
    //                     'opening_stk' => $data['opening_stk'],
    //                     't_opening_stk' => $data['t_opening_stk'],
    //                     'outward_retail' => $data['outward_retail'],
    //                     'bal_stk' => $data['bal_stk'],
    //                 ]
    //             );
    //         }

    //         return response()->json(['success' => 'Stock saved successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function store(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'stockData' => 'required|array',
                'stockData.*.oil_invoices_id' => 'required|integer',
                'stockData.*.oil_product_id' => 'required|integer',
                'stockData.*.opening_stk' => 'required|numeric',
                'stockData.*.t_opening_stk' => 'required|numeric',
                'stockData.*.outward_retail' => 'nullable|numeric',
                'stockData.*.bal_stk' => 'required|numeric',
                'selectedDate' => 'required|date', // Validate the selected date
            ]);

            // Log the received selected date (for debugging)
            \Log::info("Selected Date: " . $request->selectedDate);

            // Process the stock data
            foreach ($request->stockData as $data) {
                if (!\App\Models\OilInvoice::find($data['oil_invoices_id'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid oil invoice ID: ' . $data['oil_invoices_id'],
                    ], 422);
                }
            }

            foreach ($validatedData['stockData'] as $data) {
                Godown::updateOrCreate(
                    [
                        'oil_product_id' => $data['oil_product_id'],
                        'oil_invoices_id' => $data['oil_invoices_id'],
                        'date' => $request->selectedDate, // Store the selected date
                    ],
                    [
                        'opening_stk' => $data['opening_stk'],
                        't_opening_stk' => $data['t_opening_stk'],
                        'outward_retail' => $data['outward_retail'],
                        'bal_stk' => $data['bal_stk'],
                    ]
                );
            }

            return response()->json(['success' => 'Stock saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // old
    public function storeold(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'stockData' => 'required|array',
                'stockData.*.oil_invoices_id' => 'required|integer',
                'stockData.*.oil_product_id' => 'required|integer',
                'stockData.*.t_opening_stk' => 'nullable|numeric',
                'stockData.*.outward_retail' => 'nullable|numeric',
                'stockData.*.bal_stk' => 'nullable|numeric',
                'stockData.*.opening_stk' => 'required|numeric',
            ]);

            Log::info("Received stock data:", $validatedData['stockData']);

            foreach ($validatedData['stockData'] as $stock) {
                Godown::updateOrCreate(
                    [
                        'oil_invoices_id' => $stock['oil_invoices_id'],
                        'oil_product_id' => $stock['oil_product_id'],
                    ],
                    [
                        't_opening_stk' => $stock['t_opening_stk'] ?? 0,
                        'outward_retail' => $stock['outward_retail'] ?? 0,
                        'bal_stk' => $stock['bal_stk'] ?? 0,
                        'opening_stk' => $stock['opening_stk'] ?? 0,
                    ]
                );
            }

            return response()->json(['success' => 'Stock saved successfully!'], 200);
        } catch (\Exception $e) {
            Log::error("Stock save error: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save stock data', 'message' => $e->getMessage()], 500);
        }
    }

    public function getTotalBalStk()
    {
        $totalBalStk = Godown::selectRaw('oil_product_id, SUM(bal_stk) as total_bal_stk')
            ->groupBy('oil_product_id')
            ->get()
            ->map(function ($item) {
                $item->total_bal_stk = (float) $item->total_bal_stk; // Ensure it's numeric
                return $item;
            });

        return response()->json($totalBalStk);
    }
}
