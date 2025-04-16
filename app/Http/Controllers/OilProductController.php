<?php

namespace App\Http\Controllers;

use App\Models\OilProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OilProductController extends Controller
{
    public function index()
    {
        $products = OilProduct::all();
        return response()->json(['data' => $products], 200);
    }

    // public function store(Request $request)
    // {
    //     // Validate the incoming request data
    //     $validated = $request->validate([
    //         'op_stock' => 'required|numeric',
    //         'product_name' => 'required|string|max:255',
    //         'grade' => 'required|string|max:255',
    //         'color' => 'required|string|max:255',
    //         'mrp' => 'required|numeric',
    //         'volume' => 'required|numeric',
    //         'price_per_piece' => 'required|string|max:255',
    //         'pieces_per_case' => 'required|integer',
    //         'type' => 'required|string|in:bottle,pouches',
    //     ]);

    //     // Check if an exact match already exists
    //     $existingProduct = OilProduct::where([
    //         'op_stock' => $validated['op_stock'],
    //         'product_name' => $validated['product_name'],
    //         'grade' => $validated['grade'],
    //         'color' => $validated['color'],
    //         'mrp' => $validated['mrp'],
    //         'volume' => $validated['volume'],
    //         'price_per_piece' => $validated['price_per_piece'],
    //         'pieces_per_case' => $validated['pieces_per_case'],
    //         'type' => $validated['type'],
    //     ])->first();

    //     if ($existingProduct) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'A product with the same details already exists.'
    //         ], 409); // 409 Conflict status
    //     }

    //     // Create a new product in the database
    //     $product = OilProduct::create([
    //         'op_stock' => $validated['op_stock'],
    //         'product_name' => $validated['product_name'],
    //         'grade' => $validated['grade'],
    //         'color' => $validated['color'],
    //         'mrp' => $validated['mrp'],
    //         'volume' => $validated['volume'],
    //         'price_per_piece' => $validated['price_per_piece'],
    //         'pieces_per_case' => $validated['pieces_per_case'],
    //         'type' => $validated['type'],
    //         'added_by' => Auth::id(),
    //     ]);

    //     // Return a response with the created product
    //     return response()->json([
    //         'success' => true,
    //         'data' => $product
    //     ], 201);
    // }


    // new methid
    public function store(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'op_stock' => 'required|numeric',
        'product_name' => 'required|string|max:255',
        'grade' => 'required|string|max:255',
        'color' => 'required|string|max:255',
        'mrp' => 'required|numeric',
        'volume' => 'required|numeric',
        'price_per_piece' => 'required|string|max:255',
        'pieces_per_case' => 'required|integer',
        'type' => 'required|string|in:bottle,pouches',
        'cgst' => 'nullable|numeric', // Added validation for cgst
        'sgst' => 'nullable|numeric', // Added validation for sgst
    ]);

    // Check if an exact match already exists
    $existingProduct = OilProduct::where([
        'op_stock' => $validated['op_stock'],
        'product_name' => $validated['product_name'],
        'grade' => $validated['grade'],
        'color' => $validated['color'],
        'mrp' => $validated['mrp'],
        'volume' => $validated['volume'],
        'price_per_piece' => $validated['price_per_piece'],
        'pieces_per_case' => $validated['pieces_per_case'],
        'type' => $validated['type'],
        'cgst' => $validated['cgst'] ?? null, // Include cgst in matching
        'sgst' => $validated['sgst'] ?? null, // Include sgst in matching
    ])->first();

    if ($existingProduct) {
        return response()->json([
            'success' => false,
            'message' => 'A product with the same details already exists.'
        ], 409); // 409 Conflict status
    }

    // Create a new product in the database
    $product = OilProduct::create([
        'op_stock' => $validated['op_stock'],
        'product_name' => $validated['product_name'],
        'grade' => $validated['grade'],
        'color' => $validated['color'],
        'mrp' => $validated['mrp'],
        'volume' => $validated['volume'],
        'price_per_piece' => $validated['price_per_piece'],
        'pieces_per_case' => $validated['pieces_per_case'],
        'type' => $validated['type'],
        'cgst' => $validated['cgst'] ?? null, // Assign cgst value
        'sgst' => $validated['sgst'] ?? null, // Assign sgst value
        'added_by' => Auth::id(),
    ]);

    // Return a response with the created product
    return response()->json([
        'success' => true,
        'data' => $product
    ], 201);
}


    // public function store(Request $request)
    // {
    //     // Validate the incoming request data
    //     $validated = $request->validate([
    //         'op_stock' => 'required|numeric',
    //         'product_name' => 'required|string|max:255',
    //         'grade' => 'required|string|max:255',
    //         'color' => 'required|string|max:255',
    //         'mrp' => 'required|numeric',
    //         'volume' => 'required|numeric',
    //         'price_per_piece' => 'required|string|max:255', // Make sure the correct validation type is set
    //         'pieces_per_case' => 'required|integer',
    //         'type' => 'required|string|in:bottle,pouches', // Only allow certain values
    //     ]);

    //     // Create a new product in the database
    //     $product = OilProduct::create([
    //         'op_stock' => $validated['op_stock'],
    //         'product_name' => $validated['product_name'],
    //         'grade' => $validated['grade'],
    //         'color' => $validated['color'],
    //         'mrp' => $validated['mrp'],
    //         'volume' => $validated['volume'],
    //         'price_per_piece' => $validated['price_per_piece'],
    //         'pieces_per_case' => $validated['pieces_per_case'],
    //         'type' => $validated['type'],
    //         'added_by' => Auth::id(), // Use the logged-in user's ID for "added_by"
    //     ]);

    //     // Return a response with the created product
    //     return response()->json(['success' => true, 'data' => $product], 201);
    // }

    public function show($id)
    {
        $product = OilProduct::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['data' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $product = OilProduct::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'op_stock' => 'required|numeric',
            'product_name' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'mrp' => 'required|numeric',
            'volume' => 'required|numeric',
            'price_per_piece' => 'required|string|max:255',
            'pieces_per_case' => 'required|integer',
            'type' => 'required|string|in:bottle,pouches',
            // 'cgst' => 'numeric',
            // 'sgst'=> 'numeric' ,
        ]);

        $product->update($validated);

        return response()->json(['success' => true, 'data' => $product], 200);
    }


    public function destroy($id)
    {
        $product = OilProduct::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['success' => true], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $product = OilProduct::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $product->status = $request->status;
        $product->updated_by = Auth::id();
        $product->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully', 'data' => $product]);
    }
}
