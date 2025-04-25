<?php

namespace App\Http\Controllers;

use App\Models\OilProduct;
use App\Models\RetailSalesRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RetailSalesRecordController extends Controller
{
    public function getOilProductsWithInvoices(Request $request)
    {
        // Get the selected date from the query parameter
        $selectedDate = $request->query('selectedDate');

        // Validate the date
        if (!$selectedDate) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        // Fetch oil products along with their godown and invoice details based on the selected date
        $oilProducts = OilProduct::with(['godownStock' => function ($query) use ($selectedDate) {
            // Filter godowns by date
            $query->where('date', $selectedDate);
        }, 'oilInvoices' => function ($query) use ($selectedDate) {
            // Optionally, you can also filter invoices by the selected date if needed
            $query->whereDate('created_at', '=', $selectedDate);
        }])->get();

        return response()->json($oilProducts);
    }

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
