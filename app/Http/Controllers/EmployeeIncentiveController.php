<?php

namespace App\Http\Controllers;

use App\Models\EmployeeIncentive;
use App\Models\EmployeeProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeIncentiveController extends Controller
{
    // public function getTotalSales(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'month' => 'required|date_format:Y-m',
    //     ]);

    //     // Extract month name from the 'month' input
    //     $monthName = Carbon::createFromFormat('Y-m', $request->month)->format('F'); // "March"

    //     $totalSales = EmployeeProduct::where('employee_id', $request->employee_id)
    //         ->where('month', $monthName)
    //         ->sum('sales_done');

    //     return response()->json(['t_sale' => $totalSales]);
    // }
    public function getTotalSalesAndAmount(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'month' => 'required|date_format:Y-m',
    ]);

    $monthName = Carbon::createFromFormat('Y-m', $request->month)->format('F');

    $totalSales = EmployeeProduct::where('employee_id', $request->employee_id)
        ->where('month', $monthName)
        ->sum('sales_done');

    $totalAmount = EmployeeProduct::where('employee_id', $request->employee_id)
        ->where('month', $monthName)
        ->sum('amount');

    return response()->json([
        't_sale' => $totalSales,
        'amt' => $totalAmount
    ]);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incentives = EmployeeIncentive::with('employee')->get();
        return response()->json($incentives);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'incentive_month' => 'required|date_format:Y-m',
            'month_days' => 'required|integer|min:1|max:31',
            'holidays' => 'required|integer|min:0',
            'work_days' => 'required|integer|min:0',
            't_sale' => 'required|numeric|min:0',
            'avg_sale' => 'required|numeric|min:0',
            'amt' => 'required|numeric|min:0',
            'incentive' => 'required|numeric|min:0',
            'payment' => 'required|numeric|min:0',
            'bank_cheque' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $incentive = EmployeeIncentive::create($request->all());
            return response()->json($incentive, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create incentive', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeIncentive $employeeIncentive)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'incentive_month' => 'required|date_format:Y-m',
            'month_days' => 'required|integer|min:1|max:31',
            'holidays' => 'required|integer|min:0',
            'work_days' => 'required|integer|min:0',
            't_sale' => 'required|numeric|min:0',
            'avg_sale' => 'required|numeric|min:0',
            'amt' => 'required|numeric|min:0',
            'incentive' => 'required|numeric|min:0',
            'payment' => 'required|numeric|min:0',
            'bank_cheque' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $employeeIncentive->update($request->all());
            return response()->json($employeeIncentive, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update incentive', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeIncentive $employeeIncentive)
    {
        try {
            $employeeIncentive->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete incentive', 'message' => $e->getMessage()], 500);
        }
    }
}
