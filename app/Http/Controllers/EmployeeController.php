<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::all());
    }

    public function store(Request $request)
    {
        $employee = Employee::create($request->all());
        return response()->json($employee, 201);
    }

    public function show($id)
    {
        return response()->json(Employee::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return response()->json($employee);
    }

    public function destroy($id)
    {
        Employee::destroy($id);
        return response()->json(['message' => 'Employee deleted successfully']);
    }

    public function toggleActive($id)
{
    try {
        $employee = Employee::findOrFail($id);
        $employee->is_active = !$employee->is_active; // Toggle the status
        $employee->save();

        return response()->json(['success' => true, 'is_active' => $employee->is_active]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Employee not found or update failed'], 404);
    }
}

}
