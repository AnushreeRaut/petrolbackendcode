<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productDetail = ProductDetail::with('invoiceFeeding')->get();
        return response()->json([
            'message' => 'Product detail retrieved successfully.',
            'data' => $productDetail
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array', // Ensure products is an array
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $productDetail = ProductDetail::create($validated);

        return response()->json([
            'message' => 'Product detail created successfully.',
            'data' => $productDetail
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductDetail $productDetail)
    {
        return response()->json([
            'message' => 'Product detail retrieved successfully.',
            'data' => $productDetail->load('invoiceFeeding')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductDetail $productDetail)
    {
        $validated = $request->validate([
            'invoice_feeding_id' => 'required|exists:invoice_feedings,id',
            'invoice_date' => 'required|date',
            'products' => 'required|array',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $productDetail->update($validated);

        return response()->json([
            'message' => 'Product detail updated successfully.',
            'data' => $productDetail
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductDetail $productDetail)
    {
        $productDetail->delete();

        return response()->json([
            'message' => 'Product detail deleted successfully.'
        ]);
    }
}
