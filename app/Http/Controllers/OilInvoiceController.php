<?php

namespace App\Http\Controllers;

use App\Models\OilInvoice;
use App\Models\OilInvoiceDetail;
use App\Models\OilProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OilInvoiceController extends Controller
{
    public function getInvoicesByDate(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $invoices = OilInvoice::with('oilProduct')
            ->whereDate('created_at', $date)
            ->get()
            ->groupBy('oil_product_id');

        if ($invoices->isEmpty()) {
            return response()->json(['message' => 'No data found for this date'], 404);
        }

        $formattedInvoices = [];
        foreach ($invoices as $productId => $invoiceGroup) {
            $latestInvoice = $invoiceGroup->last();
            $formattedInvoices[] = [
                'oil_product_id' => $productId,
                'product_name' => $latestInvoice->oilProduct->product_name ?? 'N/A',
                'invoice_no' => $latestInvoice->invoice_no,
                'invoice_amt' => $latestInvoice->invoice_amt,
                'purchase_t_cases' => $latestInvoice->purchase_t_cases,
                'total_pcs' => $latestInvoice->total_pcs,
                'total_liters' => $latestInvoice->total_liters,
                'per_unit_price' => $latestInvoice->per_unit_price,
                'taxable_value' => $latestInvoice->taxable_value,
                'discount' => $latestInvoice->discount,
                'bal_amt' => $latestInvoice->bal_amt,
                'cgst' => $latestInvoice->cgst,
                'sgst' => $latestInvoice->sgst,
                'cgst_rate' => $latestInvoice->cgst_rate,
                'sgst_rate' => $latestInvoice->sgst_rate,
                'other_discounts' => $latestInvoice->other_discounts,
                'tcs' => $latestInvoice->tcs,
                'total_amt' => $latestInvoice->total_amt,
                'landing_prices' => $latestInvoice->landing_prices,
                'diff_per_pc' => $latestInvoice->diff_per_pc,
                't_stk_amt' => $latestInvoice->t_stk_amt,
            ];
        }

        return response()->json(['data' => $formattedInvoices], 200);
    }
    public function getTodaysInvoices()
    {
        $today = Carbon::today();

        $todaysInvoices = OilInvoice::with('oilProduct')
            ->whereDate('created_at', $today)
            ->get()
            ->groupBy('oil_product_id');

        $formattedInvoices = [];
        foreach ($todaysInvoices as $productId => $invoices) {
            // Assuming you only want to display the latest entry
            $latestInvoice = $invoices->last();
            $formattedInvoices[$productId] = [
                'purchase_t_cases' => $latestInvoice->purchase_t_cases,
                'total_pcs' => $latestInvoice->total_pcs,
                'total_liters' => $latestInvoice->total_liters,
                'per_unit_price' => $latestInvoice->per_unit_price,
                'taxable_value' => $latestInvoice->taxable_value,
                'discount' => $latestInvoice->discount,
                'bal_amt' => $latestInvoice->bal_amt,
                'cgst' => $latestInvoice->cgst,
                'sgst' => $latestInvoice->sgst,
                'tcs' => $latestInvoice->tcs,
                'total_amt' => $latestInvoice->total_amt,
                'landing_prices' => $latestInvoice->landing_prices,
                'diff_per_pc' => $latestInvoice->diff_per_pc,
                't_stk_amt' => $latestInvoice->t_stk_amt,

                // Add other fields you want to display
            ];
        }

        return response()->json(['data' => $formattedInvoices]);
    }

    public function index()
    {
        $products = OilProduct::with('oilinvoice')->where('status', 1)->get();
        return response()->json(['data' => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // old store
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'invoice_no' => 'required|string|max:255',
    //         'invoice_amt' => 'required|numeric',
    //         'purchase_amount' => 'nullable|numeric',
    //         'other_discounts' => 'nullable|numeric',
    //         'invoice_amount' => 'nullable|numeric',
    //         'invoices' => 'required|array', // Expecting an array of invoices
    //         'invoices.*.oil_product_id' => 'required|exists:oil_products,id',
    //         'invoices.*.purchase_t_cases' => 'nullable|integer',
    //         'invoices.*.total_cases' => 'nullable|integer',
    //         'invoices.*.total_liters' => 'nullable|numeric',
    //         'invoices.*.per_unit_price' => 'nullable|numeric',
    //         'invoices.*.taxable_value' => 'nullable|numeric',
    //         'invoices.*.discount' => 'nullable|numeric',
    //         'invoices.*.bal_amt' => 'nullable|numeric',
    //         'invoices.*.cgst' => 'nullable|numeric',
    //         'invoices.*.sgst' => 'nullable|numeric',
    //         'invoices.*.tcs' => 'nullable|numeric',
    //         'invoices.*.total_amt' => 'nullable|numeric',
    //         'invoices.*.total_pcs' => 'nullable|integer',
    //         'invoices.*.landing_prices' => 'nullable|numeric',
    //         'invoices.*.diff_per_pc' => 'nullable|numeric',
    //         'invoices.*.t_stk_amt' => 'nullable|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Store the Oil Invoice Details (single record per invoice)
    //     $oilInvoiceDetail = OilInvoiceDetail::create([
    //         'invoice_no' => $request->invoice_no,
    //         'invoice_amt' => $request->invoice_amt,
    //         'purchase_amount' => $request->purchase_amount ?? null,
    //         'other_discounts' => $request->other_discounts ?? null,
    //         'invoice_amount' => $request->invoice_amount ?? null,
    //     ]);

    //     // Filter invoices where all specified fields are NULL
    //     $filteredInvoices = array_filter($request->invoices, function ($invoice) {
    //         return !(
    //             is_null($invoice['purchase_t_cases'] ?? null) &&
    //             is_null($invoice['total_cases'] ?? null) &&
    //             is_null($invoice['total_liters'] ?? null) &&
    //             is_null($invoice['per_unit_price'] ?? null) &&
    //             is_null($invoice['bal_amt'] ?? null)
    //         );
    //     });

    //     // Store valid invoices
    //     foreach ($filteredInvoices as $invoiceData) {
    //         OilInvoice::create([
    //             'invoice_no' => $request->invoice_no,
    //             'invoice_amt' => $request->invoice_amt,
    //             'oil_product_id' => $invoiceData['oil_product_id'],
    //             'purchase_t_cases' => $invoiceData['purchase_t_cases'] ?? null,
    //             'total_cases' => $invoiceData['total_cases'] ?? null,
    //             'total_liters' => $invoiceData['total_liters'] ?? null,
    //             'per_unit_price' => $invoiceData['per_unit_price'] ?? null,
    //             'taxable_value' => $invoiceData['taxable_value'] ?? null,
    //             'discount' => $invoiceData['discount'] ?? null,
    //             'bal_amt' => $invoiceData['bal_amt'] ?? null,
    //             'cgst' => $invoiceData['cgst'] ?? null,
    //             'sgst' => $invoiceData['sgst'] ?? null,
    //             'tcs' => $invoiceData['tcs'] ?? null,
    //             'total_amt' => $invoiceData['total_amt'] ?? null,
    //             'total_pcs' => $invoiceData['total_pcs'] ?? null,
    //             'landing_prices' => $invoiceData['landing_prices'] ?? null,
    //             'diff_per_pc' => $invoiceData['diff_per_pc'] ?? null,
    //             't_stk_amt' => $invoiceData['t_stk_amt'] ?? null,
    //         ]);
    //     }

    //     return response()->json(['message' => 'Oil Invoice and Details saved successfully!'], 201);
    // }
    // new store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no' => 'required|string|max:255',
            'invoice_amt' => 'required|numeric',
            'purchase_amount' => 'nullable|numeric',
            'other_discounts' => 'nullable|numeric',
            'invoice_amount' => 'required|numeric', // Ensure this is required
            'invoices' => 'required|array',
            'invoices.*.oil_product_id' => 'required|exists:oil_products,id',
            'invoices.*.purchase_t_cases' => 'nullable|integer',
            'invoices.*.total_cases' => 'nullable|integer',
            'invoices.*.total_liters' => 'nullable|numeric',
            'invoices.*.per_unit_price' => 'nullable|numeric',
            'invoices.*.taxable_value' => 'nullable|numeric',
            'invoices.*.discount' => 'nullable|numeric',
            'invoices.*.bal_amt' => 'nullable|numeric',
            'invoices.*.cgst' => 'nullable|numeric',
            'invoices.*.sgst' => 'nullable|numeric',
            'invoices.*.tcs' => 'nullable|numeric',
            'invoices.*.total_amt' => 'nullable|numeric',
            'invoices.*.total_pcs' => 'nullable|integer',
            'invoices.*.landing_prices' => 'nullable|numeric',
            'invoices.*.diff_per_pc' => 'nullable|numeric',
            'invoices.*.t_stk_amt' => 'nullable|numeric',
            'invoices.*.cgst_rate' => 'nullable|numeric',
            'invoices.*.sgst_rate' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // **Check if invoice_amt matches invoice_amount**
        if ($request->invoice_amt != $request->invoice_amount) {
            return response()->json(['error' => 'Invoice amount does not match!'], 422);
        }

        // Store the Oil Invoice Details
        $oilInvoiceDetail = OilInvoiceDetail::create([
            'invoice_no' => $request->invoice_no,
            'invoice_amt' => $request->invoice_amt,
            'purchase_amount' => $request->purchase_amount ?? null,
            'other_discounts' => $request->other_discounts ?? null,
            'invoice_amount' => $request->invoice_amount ?? null,
        ]);

        // Filter invoices where all specified fields are NULL
        $filteredInvoices = array_filter($request->invoices, function ($invoice) {
            return !(
                is_null($invoice['purchase_t_cases'] ?? null) &&
                is_null($invoice['total_cases'] ?? null) &&
                is_null($invoice['total_liters'] ?? null) &&
                is_null($invoice['per_unit_price'] ?? null) &&
                is_null($invoice['bal_amt'] ?? null)
            );
        });

        // Store valid invoices
        foreach ($filteredInvoices as $invoiceData) {
            OilInvoice::create([
                'invoice_no' => $request->invoice_no,
                'invoice_amt' => $request->invoice_amt,
                'oil_product_id' => $invoiceData['oil_product_id'],
                'purchase_t_cases' => $invoiceData['purchase_t_cases'] ?? null,
                'total_cases' => $invoiceData['total_cases'] ?? null,
                'total_liters' => $invoiceData['total_liters'] ?? null,
                'per_unit_price' => $invoiceData['per_unit_price'] ?? null,
                'taxable_value' => $invoiceData['taxable_value'] ?? null,
                'discount' => $invoiceData['discount'] ?? null,
                'bal_amt' => $invoiceData['bal_amt'] ?? null,
                'cgst' => $invoiceData['cgst'] ?? null,
                'sgst' => $invoiceData['sgst'] ?? null,
                'tcs' => $invoiceData['tcs'] ?? null,
                'total_amt' => $invoiceData['total_amt'] ?? null,
                'total_pcs' => $invoiceData['total_pcs'] ?? null,
                'landing_prices' => $invoiceData['landing_prices'] ?? null,
                'diff_per_pc' => $invoiceData['diff_per_pc'] ?? null,
                't_stk_amt' => $invoiceData['t_stk_amt'] ?? null,
                'sgst_rate' => $invoiceData['sgst_rate']  ?? null,
                'cgst_rate' => $invoiceData['cgst_rate']  ?? null,
            ]);
        }

        return response()->json(['message' => 'Oil Invoice and Details saved successfully!'], 201);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'invoice_no' => 'required|string|max:255',
    //         'invoice_amt' => 'required|numeric',
    //         'purchase_amount' => 'nullable|numeric',
    //         'other_discounts' => 'nullable|numeric',
    //         'invoice_amount' => 'nullable|numeric',
    //         'invoices' => 'required|array', // Expecting an array of invoices
    //         'invoices.*.oil_product_id' => 'required|exists:oil_products,id',
    //         'invoices.*.purchase_t_cases' => 'nullable|integer',
    //         'invoices.*.total_cases' => 'nullable|integer',
    //         'invoices.*.total_liters' => 'nullable|numeric',
    //         'invoices.*.per_unit_price' => 'nullable|numeric',
    //         'invoices.*.taxable_value' => 'nullable|numeric',
    //         'invoices.*.discount' => 'nullable|numeric',
    //         'invoices.*.bal_amt' => 'nullable|numeric',
    //         'invoices.*.cgst' => 'nullable|numeric',
    //         'invoices.*.sgst' => 'nullable|numeric',
    //         'invoices.*.tcs' => 'nullable|numeric',
    //         'invoices.*.total_amt' => 'nullable|numeric',
    //         'invoices.*.total_pcs' => 'nullable|integer',
    //         'invoices.*.landing_prices' => 'nullable|numeric',
    //         'invoices.*.diff_per_pc' => 'nullable|numeric',
    //         'invoices.*.t_stk_amt' => 'nullable|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Store the Oil Invoice Details (single record per invoice)
    //     $oilInvoiceDetail = OilInvoiceDetail::create([
    //         'invoice_no' => $request->invoice_no,
    //         'invoice_amt' => $request->invoice_amt,
    //         'purchase_amount' => $request->purchase_amount ?? null,
    //         'other_discounts' => $request->other_discounts ?? null,
    //         'invoice_amount' => $request->invoice_amount ?? null,
    //     ]);

    //     // Store multiple Oil Invoices linked to the same invoice_no
    //     foreach ($request->invoices as $invoiceData) {
    //         OilInvoice::create([
    //             'invoice_no' => $request->invoice_no,
    //             'invoice_amt' => $request->invoice_amt,
    //             'oil_product_id' => $invoiceData['oil_product_id'],
    //             'purchase_t_cases' => $invoiceData['purchase_t_cases'] ?? null,
    //             'total_cases' => $invoiceData['total_cases'] ?? null,
    //             'total_liters' => $invoiceData['total_liters'] ?? null,
    //             'per_unit_price' => $invoiceData['per_unit_price'] ?? null,
    //             'taxable_value' => $invoiceData['taxable_value'] ?? null,
    //             'discount' => $invoiceData['discount'] ?? null,
    //             'bal_amt' => $invoiceData['bal_amt'] ?? null,
    //             'cgst' => $invoiceData['cgst'] ?? null,
    //             'sgst' => $invoiceData['sgst'] ?? null,
    //             'tcs' => $invoiceData['tcs'] ?? null,
    //             'total_amt' => $invoiceData['total_amt'] ?? null,
    //             'total_pcs' => $invoiceData['total_pcs'] ?? null,
    //             'landing_prices' => $invoiceData['landing_prices'] ?? null,
    //             'diff_per_pc' => $invoiceData['diff_per_pc'] ?? null,
    //             't_stk_amt' => $invoiceData['t_stk_amt'] ?? null,
    //         ]);
    //     }

    //     return response()->json(['message' => 'Oil Invoice and Details saved successfully!'], 201);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'invoice_no' => 'required|string|max:255',
    //         'invoice_amt' => 'required|numeric',
    //         'invoices' => 'required|array', // Expecting an array of invoices
    //         'invoices.*.oil_product_id' => 'required|exists:oil_products,id',
    //         'invoices.*.purchase_t_cases' => 'nullable|integer',
    //         'invoices.*.total_cases' => 'nullable|integer',
    //         'invoices.*.total_liters' => 'nullable|numeric',
    //         'invoices.*.per_unit_price' => 'nullable|numeric',
    //         'invoices.*.taxable_value' => 'nullable|numeric',
    //         'invoices.*.discount' => 'nullable|numeric',
    //         'invoices.*.bal_amt' => 'nullable|numeric',
    //         'invoices.*.cgst' => 'nullable|numeric',
    //         'invoices.*.sgst' => 'nullable|numeric',
    //         'invoices.*.tcs' => 'nullable|numeric',
    //         'invoices.*.total_amt' => 'nullable|numeric',
    //         'invoices.*.total_pcs' => 'nullable|integer',
    //         'invoices.*.landing_prices' => 'nullable|numeric',
    //         'invoices.*.diff_per_pc' => 'nullable|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     foreach ($request->invoices as $invoiceData) {
    //         OilInvoice::create([
    //             'invoice_no' => $request->invoice_no,
    //             'invoice_amt' => $request->invoice_amt,
    //             'oil_product_id' => $invoiceData['oil_product_id'],
    //             'purchase_t_cases' => $invoiceData['purchase_t_cases'] ?? null,
    //             'total_cases' => $invoiceData['total_cases'] ?? null,
    //             'total_liters' => $invoiceData['total_liters'] ?? null,
    //             'per_unit_price' => $invoiceData['per_unit_price'] ?? null,
    //             'taxable_value' => $invoiceData['taxable_value'] ?? null,
    //             'discount' => $invoiceData['discount'] ?? null,
    //             'bal_amt' => $invoiceData['bal_amt'] ?? null,
    //             'cgst' => $invoiceData['cgst'] ?? null,
    //             'sgst' => $invoiceData['sgst'] ?? null,
    //             'tcs' => $invoiceData['tcs'] ?? null,
    //             'total_amt' => $invoiceData['total_amt'] ?? null,
    //             'total_pcs' => $invoiceData['total_pcs'] ?? null,
    //             'landing_prices' => $invoiceData['landing_prices'] ?? null,
    //             'diff_per_pc' => $invoiceData['diff_per_pc'] ?? null,
    //         ]);
    //     }

    //     return response()->json(['message' => 'Oil Invoices saved successfully!'], 201);
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = OilInvoice::with('oilProduct')->findOrFail($id);
        return response()->json(['data' => $invoice], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = OilInvoice::findOrFail($id);

        $request->validate([
            'invoice_no' => 'required|string|unique:oil_invoices,invoice_no,' . $id,
            'invoice_amt' => 'required|numeric',
            'oil_product_id' => 'required|exists:oil_products,id',
            'purchase_t_cases' => 'required|integer',
            'total_cases' => 'required|integer',
            'total_liters' => 'required|numeric',
            'per_unit_price' => 'required|numeric',
            'taxable_value' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'bal_amt' => 'nullable|numeric',
            'cgst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'tcs' => 'nullable|numeric',
            'total_amt' => 'required|numeric',
            'total_pcs' => 'required|integer',
            'landing_prices' => 'nullable|numeric',
            'purchase_amount' => 'required|numeric',
            'other_discounts' => 'nullable|numeric',
            'invoice_amount' => 'required|numeric',
            'diff_per_pc' => 'required|numeric'
        ]);

        $invoice->update($request->all());
        return response()->json(['message' => 'Invoice updated successfully', 'data' => $invoice], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = OilInvoice::findOrFail($id);
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }
}
