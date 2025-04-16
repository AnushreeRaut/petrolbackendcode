<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Get all products
    public function index()
    {
        return response()->json(Product::all());
    }

    // Store a new product
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        $product = Product::create([
            'product_name' => $request->product_name,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($product);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        $product->update([
            'product_name' => $request->product_name,
            'is_active' => $request->is_active ?? $product->is_active,
        ]);

        return response()->json($product);
    }

    // Delete a product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json(['is_active' => $product->is_active]);
    }
}
