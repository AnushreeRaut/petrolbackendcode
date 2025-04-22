<?php

namespace App\Http\Controllers;

use App\Models\OilProduct;
use App\Models\RetailSalesRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RetailSalesRecordController extends Controller
{
    // public function getOilProductsWithInvoices()
    // {
    //     $oilProducts = OilProduct::whereHas('oilInvoices', function ($query) {
    //         $query->whereDate('created_at', now()->toDateString());
    //     })->get();

    //     return response()->json($oilProducts);
    // }
    // public function getOilProductsWithInvoices()
    // {
    //     $oilProducts = OilProduct::whereHas('oilInvoices', function ($query) {
    //         $query->whereDate('created_at', now()->toDateString());
    //     })->get();

    //     $oilProducts = OilProduct::select('oil_products.*', 'godowns.outward_retail')
    //         ->leftJoin('godowns', 'oil_products.id', '=', 'godowns.oil_product_id')
    //         ->whereHas('oilInvoices', function ($query) {
    //             $query->whereDate('created_at', now()->toDateString());
    //         })
    //         ->get();

    //     return response()->json($oilProducts);
    // }

    // public function getOilProductsWithInvoices()
    // {
    //     $oilProducts = OilProduct::select('oil_products.*', 'godowns.outward_retail')
    //         ->leftJoin('godowns', 'oil_products.id', '=', 'godowns.oil_product_id')
    //         ->whereHas('oilInvoices', function ($query) {
    //             $query->whereDate('oil_invoices.created_at', now()->toDateString());
    //         })
    //         ->get();

    //     return response()->json($oilProducts);
    // }
    // public function getOilProductsWithInvoices()
    // {
    //     $today = Carbon::today()->toDateString(); // Get today's date

    //     $oilProducts = OilProduct::whereHas('oilInvoices', function ($query) use ($today) {
    //             $query->whereDate('created_at', $today);
    //         })
    //         ->with([
    //             'oilInvoices' => function ($query) use ($today) {
    //                 $query->whereDate('created_at', $today);
    //             },
    //             'oilInvoices.godowns' => function ($query) {
    //                 $query->select('id', 'oil_invoices_id', 'oil_product_id', 'outward_retail');
    //             }
    //         ])
    //         ->get();

    //     return response()->json($oilProducts);
    // }
    public function getOilProductsWithInvoices()
{
    $today = Carbon::today()->toDateString(); // Get today's date

    $oilProducts = OilProduct::whereHas('oilInvoices', function ($query) use ($today) {
            $query->whereDate('created_at', $today);
        })
        ->with([
            'oilInvoices' => function ($query) use ($today) {
                // $query->whereDate('created_at', $today);
            },
            'oilInvoices.godowns' => function ($query) {
                $query->select('id', 'oil_invoices_id', 'oil_product_id', 'outward_retail');
            }
        ])
        ->get();

    // Add computed `inward_to_retail` field
    $oilProducts->each(function ($product) {
        $product->inward_to_retail = $product->oilInvoices->flatMap->godowns->sum('outward_retail');
    });

    return response()->json($oilProducts);
}

    // Store Retail Sales Records


    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.oil_product_id' => 'required|exists:oil_products,id',
            'products.*.opening_stk_pcs' => 'nullable|numeric',
            'products.*.inward_to_retail' => 'nullable|numeric',
            'products.*.total_op_stk' => 'nullable|numeric',
            'products.*.quantity_Sale' => 'nullable|numeric',
            'products.*.bal_stk' => 'nullable|numeric',
            'products.*.sale_amt' => 'nullable|numeric',
            'products.*.discount_amt' => 'nullable|numeric',
            'products.*.act_sale_amt' => 'nullable|numeric',
        ]);

        foreach ($request->products as $product) {
            RetailSalesRecord::create([
                'oil_product_id' => $product['oil_product_id'],
                'opening_stk_pcs' => $product['opening_stk_pcs'] ?? 0,
                'inward_to_retail' => $product['inward_to_retail'] ?? 0,
                'total_op_stk' => $product['total_op_stk'] ?? 0,
                'quality_Sale' => $product['quantity_Sale'] ?? 0, // Ensure correct field mapping
                'bal_stk' => $product['bal_stk'] ?? 0,
                'sale_amt' => $product['sale_amt'] ?? 0,
                'discount_amt' => $product['discount_amt'] ?? 0,
                'act_sale_amt' => $product['act_sale_amt'] ?? 0,
            ]);
        }

        return response()->json(['message' => 'Stock records saved successfully!'], 201);
    }
}
