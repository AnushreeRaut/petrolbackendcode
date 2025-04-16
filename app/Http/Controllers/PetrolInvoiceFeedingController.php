<?php

namespace App\Http\Controllers;

use App\Models\AddPetrolInvoice;
use App\Models\PetrolInvoiceFeeding;
use App\Models\Tank;
use App\Models\TankInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;


class PetrolInvoiceFeedingController extends Controller
{
    // public function getGroupedInvoices()
    // {
    //     // Fetch today's date
    //     $today = now()->toDateString(); // Get current date in 'YYYY-MM-DD' format

    //     // Fetch data ordered by invoice number and filter for today's records
    //     $invoices = DB::table('petrol_invoice_feeding')
    //         ->join('tanks', 'petrol_invoice_feeding.tank_id', '=', 'tanks.id')
    //         ->select(
    //             'petrol_invoice_feeding.id', // Include the ID for React keys
    //             'petrol_invoice_feeding.invoice_no',
    //             'petrol_invoice_feeding.kl_qty',
    //             'petrol_invoice_feeding.rate',
    //             'petrol_invoice_feeding.value',
    //             'petrol_invoice_feeding.tax_amt',
    //             'petrol_invoice_feeding.prod_amt',
    //             'petrol_invoice_feeding.vat_lst_value',
    //             'petrol_invoice_feeding.vat_lst',
    //             'petrol_invoice_feeding.cess',
    //             'petrol_invoice_feeding.tcs',
    //             'petrol_invoice_feeding.total_amt',
    //             'petrol_invoice_feeding.tds_percent',
    //             'petrol_invoice_feeding.lfr_rate',
    //             'petrol_invoice_feeding.cgst',
    //             'petrol_invoice_feeding.sgst',
    //             'petrol_invoice_feeding.tds_lfr',
    //             'tanks.product', // Select the product from tanks table
    //         )
    //         ->whereDate('petrol_invoice_feeding.created_at', $today) // Filter only today's records
    //         ->orderBy('petrol_invoice_feeding.invoice_no')
    //         ->get();

    //     // Return the plain array
    //     return response()->json([
    //         'success' => true,
    //         'data' => $invoices,
    //     ]);
    // }
    // public function getGroupedInvoices()
    // {
    //     // Fetch today's date
    //     $today = now()->toDateString(); // Get current date in 'YYYY-MM-DD' format

    //     // Fetch data ordered by invoice number and filter for today's records
    //     $invoices = DB::table('petrol_invoice_feeding')
    //         ->join('tanks', 'petrol_invoice_feeding.tank_id', '=', 'tanks.id')
    //         ->select(
    //             'petrol_invoice_feeding.id', // Include the ID for React keys
    //             'petrol_invoice_feeding.invoice_no',
    //             'petrol_invoice_feeding.kl_qty',
    //             'petrol_invoice_feeding.rate',
    //             'petrol_invoice_feeding.value',
    //             'petrol_invoice_feeding.tax_amt',
    //             'petrol_invoice_feeding.prod_amt',
    //             'petrol_invoice_feeding.vat_lst_value',
    //             'petrol_invoice_feeding.vat_lst',
    //             'petrol_invoice_feeding.cess',
    //             'petrol_invoice_feeding.tcs',
    //             'petrol_invoice_feeding.total_amt',
    //             'petrol_invoice_feeding.tds_percent',
    //             'petrol_invoice_feeding.lfr_rate',
    //             'petrol_invoice_feeding.cgst',
    //             'petrol_invoice_feeding.sgst',
    //             'petrol_invoice_feeding.tds_lfr',
    //             'petrol_invoice_feeding.tank_id', // ✅ Added tank_id
    //             'tanks.product' // Select the product from tanks table
    //         )
    //         ->whereDate('petrol_invoice_feeding.created_at', $today) // Filter only today's records
    //         ->orderBy('petrol_invoice_feeding.invoice_no')
    //         ->get();

    //     // Return the plain array
    //     return response()->json([
    //         'success' => true,
    //         'data' => $invoices,
    //     ]);
    // }

    public function getGroupedInvoices(Request $request)
    {
        $date = $request->query('date'); // e.g. '2025-02-08'

        if (!$date) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $invoices = DB::table('petrol_invoice_feeding')
            ->join('tanks', 'petrol_invoice_feeding.tank_id', '=', 'tanks.id')
            ->select(
                'petrol_invoice_feeding.id',
                'petrol_invoice_feeding.invoice_no',
                'petrol_invoice_feeding.kl_qty',
                'petrol_invoice_feeding.rate',
                'petrol_invoice_feeding.value',
                'petrol_invoice_feeding.tax_amt',
                'petrol_invoice_feeding.prod_amt',
                'petrol_invoice_feeding.vat_lst_value',
                'petrol_invoice_feeding.vat_lst',
                'petrol_invoice_feeding.cess',
                'petrol_invoice_feeding.tcs',
                'petrol_invoice_feeding.total_amt',
                'petrol_invoice_feeding.tds_percent',
                'petrol_invoice_feeding.lfr_rate',
                'petrol_invoice_feeding.cgst',
                'petrol_invoice_feeding.sgst',
                'petrol_invoice_feeding.tds_lfr',
                'petrol_invoice_feeding.tank_id',
                'tanks.product'
            )
            ->whereDate('petrol_invoice_feeding.date', $date) // ✅ use the correct column
            ->orderBy('petrol_invoice_feeding.invoice_no')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }



    // =====================================
    public function getGroupedInvoicesdata()
    {
        // Fetch today's date
        $today = now()->toDateString(); // Get current date in 'YYYY-MM-DD' format

        // Fetch data ordered by invoice number and filter for today's records
        $invoices = DB::table('petrol_invoice_feeding')
            ->join('tanks', 'petrol_invoice_feeding.tank_id', '=', 'tanks.id')
            ->join('add_petrol_invoice', 'petrol_invoice_feeding.tank_id', '=', 'add_petrol_invoice.tank_id') // Join with add_petrol_invoice
            ->select(
                'petrol_invoice_feeding.id',
                'petrol_invoice_feeding.invoice_no',
                'petrol_invoice_feeding.kl_qty',
                'petrol_invoice_feeding.rate',
                'petrol_invoice_feeding.value',
                'petrol_invoice_feeding.tax_amt',
                'petrol_invoice_feeding.prod_amt',
                'petrol_invoice_feeding.vat_lst_value',
                'petrol_invoice_feeding.vat_lst',
                'petrol_invoice_feeding.cess',
                'petrol_invoice_feeding.tcs',
                'petrol_invoice_feeding.total_amt',
                'petrol_invoice_feeding.tds_percent',
                'petrol_invoice_feeding.lfr_rate',
                'petrol_invoice_feeding.cgst',
                'petrol_invoice_feeding.sgst',
                'petrol_invoice_feeding.tds_lfr',
                'tanks.product',
                'add_petrol_invoice.rate_per_unit', // Include rate_per_unit from add_petrol_invoice
                'add_petrol_invoice.tax_amt_per_amt',
                'add_petrol_invoice.cess_per_unit',
                'add_petrol_invoice.tcs_per_unit',
            )
            ->whereDate('petrol_invoice_feeding.created_at', $today) // Filter only today's records
            ->orderBy('petrol_invoice_feeding.created_at', 'asc')
            ->get();

        // Return the plain array
        return response()->json([
            'success' => true,
            'data' => $invoices,
        ]);
    }

    // ======================================

    // public function getGroupedInvoices()
    // {
    //     // Fetch data ordered by invoice number
    //     $invoices = DB::table('petrol_invoice_feeding')
    //         ->select(
    //             'id', // Include the ID for React keys
    //             'invoice_no',
    //             'kl_qty',
    //             'rate',
    //             'value',
    //             'tax_amt',
    //             'prod_amt',
    //             'vat_lst_value',
    //             'vat_lst',
    //             'cess',
    //             'tcs',
    //             'total_amt',
    //             'tds_percent',
    //             'lfr_rate',
    //             'cgst',
    //             'sgst',
    //             'tds_lfr'
    //         )
    //         ->orderBy('invoice_no')
    //         ->get();

    //     // Return the plain array
    //     return response()->json([
    //         'success' => true,
    //         'data' => $invoices,
    //     ]);
    // }


    // public function getGroupedInvoices()
    // {
    //     // Fetch data ordered by invoice number and join with tanks to get the product
    //     $invoices = DB::table('petrol_invoice_feeding')
    //         ->join('tanks', 'petrol_invoice_feeding.tank_id', '=', 'tanks.id')
    //         ->select(
    //             'petrol_invoice_feeding.id', // Include the ID for React keys
    //             'petrol_invoice_feeding.invoice_no',
    //             'petrol_invoice_feeding.kl_qty',
    //             'petrol_invoice_feeding.rate',
    //             'petrol_invoice_feeding.value',
    //             'petrol_invoice_feeding.tax_amt',
    //             'petrol_invoice_feeding.prod_amt',
    //             'petrol_invoice_feeding.vat_lst_value',
    //             'petrol_invoice_feeding.vat_lst',
    //             'petrol_invoice_feeding.cess',
    //             'petrol_invoice_feeding.tcs',
    //             'petrol_invoice_feeding.total_amt',
    //             'petrol_invoice_feeding.tds_percent',
    //             'petrol_invoice_feeding.lfr_rate',
    //             'petrol_invoice_feeding.cgst',
    //             'petrol_invoice_feeding.sgst',
    //             'petrol_invoice_feeding.tds_lfr',
    //             'tanks.product' // Select the product from tanks table
    //         )
    //         ->orderBy('petrol_invoice_feeding.invoice_no')
    //         ->get();

    //     // Return the plain array
    //     return response()->json([
    //         'success' => true,
    //         'data' => $invoices,
    //     ]);
    // }


    public function fetchInvoiceData($tank_id)
    {
        $invoices = AddPetrolInvoice::where('tank_id', $tank_id)->get();
        return response()->json($invoices);
    }


    public function getTankProducts()
    {
        $tanks = Tank::all(); // Fetch all tanks
        $products = $tanks->pluck('product', 'id'); // Get product names with tank IDs as keys
        return response()->json($products); // Return products as JSON
    }

    public function index()
    {
        $invoices = PetrolInvoiceFeeding::all();
        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => 'required|exists:tanks,id',
            'invoice_no' => 'required|string|max:255',
            'kl_qty' => 'required|numeric',
            'rate' => 'required|numeric',
            'value' => 'required|numeric',
            'tax_amt' => 'required|numeric',
            'prod_amt' => 'required|numeric',
            'vat_lst_value' => 'required|numeric',
            'vat_lst' => 'required|numeric',
            'cess' => 'required|numeric',
            'tcs' => 'required|numeric',
            'total_amt' => 'required|numeric',
            'tds_percent' => 'required|numeric',
            'lfr_rate' => 'required|numeric',
            'cgst' => 'required|numeric',
            'sgst' => 'required|numeric',
            'tds_lfr' => 'required|numeric',
            'date' => 'required|date', // Validate date field
        ]);

        $invoice = PetrolInvoiceFeeding::create(array_merge($validated, [
            'added_by' => auth()->id(),
        ]));

        return response()->json(['message' => 'Invoice created successfully.', 'data' => $invoice]);
    }

    public function show($id)
    {
        $invoice = PetrolInvoiceFeeding::findOrFail($id);
        return response()->json($invoice);
    }
    public function update(Request $request, $id)
    {
        $invoice = PetrolInvoiceFeeding::findOrFail($id); // Or handle the case where the invoice is not found
        $invoice->update($request->all());
        return response()->json(['message' => 'Invoice updated successfully', 'data' => $invoice]);
    }

    // public function update(Request $request, $id)
    // {
    //     $invoice = PetrolInvoiceFeeding::findOrFail($id);

    //     $validated = $request->validate([
    //         'tank_id' => 'required|exists:tanks,id',
    //         'invoice_no' => 'required|string|max:255',
    //         'kl_qty' => 'required|numeric',
    //         'rate' => 'required|numeric',
    //         'value' => 'required|numeric',
    //         'tax_amt' => 'required|numeric',
    //         'prod_amt' => 'required|numeric',
    //         'vat_percent' => 'required|numeric',
    //         'vat_lst' => 'required|numeric',
    //         'cess' => 'required|numeric',
    //         'tcs' => 'required|numeric',
    //         'total_amt' => 'required|numeric',
    //         'tds_percent' => 'required|numeric',
    //         'lfr_rate' => 'required|numeric',
    //         'cgst' => 'required|numeric',
    //         'sgst' => 'required|numeric',
    //         'tds_lfr' => 'required|numeric',
    //     ]);

    //     $invoice->update(array_merge($validated, [
    //         'updated_by' => auth()->id(),
    //     ]));

    //     return response()->json(['message' => 'Invoice updated successfully.', 'data' => $invoice]);
    // }

    public function destroy($id)
    {
        $invoice = PetrolInvoiceFeeding::findOrFail($id);
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully.']);
    }

    // TankController.php
    public function getTankDetails($tank_id)
    {
        $tank = Tank::find($tank_id); // Assuming you have a Tank model
        if (!$tank) {
            return response()->json(['message' => 'Tank not found'], 404);
        }
        return response()->json($tank);
    }
}
