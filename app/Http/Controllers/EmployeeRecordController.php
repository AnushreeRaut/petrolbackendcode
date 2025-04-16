<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeRecordController extends Controller
{
    // public function getThisMonthEmployeeProducts()
    // {
    //     $data = EmployeeProduct::with('employee', 'product')
    //         ->whereMonth('created_at', now()->month)
    //         ->whereYear('created_at', now()->year)
    //         ->get()
    //         ->groupBy('employee_id');

    //     return response()->json($data);
    // }

  // In EmployeeRecordController.php
  public function getThisMonthEmployeeProducts()
  {
      $currentMonth = Carbon::now()->format('F'); // Gets full month name (e.g., "March")

      $data = EmployeeProduct::with(['employee', 'product'])
          ->where('month', $currentMonth) // Ensure stored months are in "F" format
          ->get()
          ->groupBy('employee_id');

      return response()->json($data);
  }

}
