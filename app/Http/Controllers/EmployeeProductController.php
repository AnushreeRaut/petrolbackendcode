<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade

class EmployeeProductController extends Controller
{
    // Index - Get all records grouped by employee and month
    public function index()
    {
        $employeeProducts = EmployeeProduct::with(['employee', 'product'])
            ->get()
            ->groupBy(function ($item) {
                return $item->employee_id . '-' . $item->month; // Group by employee_id and month
            });

        $formattedData = [];
        foreach ($employeeProducts as $key => $products) {
            list($employeeId, $month) = explode('-', $key);
            $totalAmount = $products->sum('amount');

            $formattedData[] = [
                'employee_id' => $employeeId,
                'month' => $month,
                'total_amount' => $totalAmount,
                'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'product_id' => $product->product_id,
                        'product_name' => $product->product->product_name,
                        'sales_done' => $product->sales_done,
                        'commission' => $product->commission,
                        'amount' => $product->amount,
                        'is_active' => $product->is_active,
                    ];
                }),
                'employee' => $products->first()->employee,
            ];
        }

        return response()->json($formattedData);
    }

    // Show - Get a single record by employee_id and month
    public function show($employeeId, $month)
    {
        $records = EmployeeProduct::with('product')
            ->where('employee_id', $employeeId)
            ->where('month', $month)
            ->get();

        if ($records->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $products = $records->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'sales_done' => $item->sales_done,
                'commission' => $item->commission,
                'amount' => $item->amount,
            ];
        });

        $totalAmount = $records->sum('amount');

        return response()->json([
            'employee_id' => $employeeId,
            'month' => $month,
            'products' => $products,
            'total_amount' => $totalAmount,
        ]);
    }

    // Store - Create new employee product records
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string',
            'total_amount' => 'required|numeric',
            'product_sales' => 'required|array',
            'product_sales.*.product_id' => 'required|exists:products,id',
            'product_sales.*.sales_done' => 'required|numeric|min:0',
            'product_sales.*.commission' => 'required|numeric|min:0',
            'product_sales.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $products = $request->product_sales;

            foreach ($products as $product) {
                EmployeeProduct::create([
                    'employee_id' => $request->employee_id,
                    'product_id' => $product['product_id'],
                    'month' => $request->month,
                    'sales_done' => $product['sales_done'],
                    'commission' => $product['commission'],
                    'amount' => $product['amount'],
                    'total_amount' => $request->total_amount,
                    'is_active' => $request->is_active ?? true,
                ]);
            }

            DB::commit(); // Commit transaction
            return response()->json(['message' => 'Employee products saved successfully!']);
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction on error
            return response()->json(['message' => 'Failed to save employee products: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'string',
            'product_sales' => 'array',
            'product_sales.*.product_id' => 'required|exists:products,id',
            'product_sales.*.sales_done' => 'numeric|min:0',
            'product_sales.*.commission' => 'numeric|min:0',
            'product_sales.*.amount' => 'numeric|min:0',
        ]);

        $employeeProduct = EmployeeProduct::findOrFail($id);

        // Update the main employee product record
        $employeeProduct->update([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'total_amount' => collect($request->product_sales)->sum('amount'),
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        // Sync product sales (delete old and insert new ones)
        $employeeProduct->products()->detach(); // Remove existing records

        foreach ($request->product_sales as $product) {
            $employeeProduct->products()->attach($product['product_id'], [
                'sales_done' => $product['sales_done'],
                'commission' => $product['commission'],
                'amount' => $product['amount'],
            ]);
        }

        return response()->json(['message' => 'Employee product updated successfully!', 'data' => $employeeProduct], 200);
    }


    // Destroy - Delete employee product records
    public function destroy($id)
    {
        try {
            EmployeeProduct::destroy($id);
            return response()->json(['message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete data: ' . $e->getMessage()], 500);
        }
    }
}
