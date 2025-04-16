<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Fetch all roles
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Store a new role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $role = Role::create(array_merge($validated, [
            'added_by' => auth()->id(), // Assuming user authentication
        ]));

        return response()->json($role, 201);
    }

    // Show a specific role
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    // Update a specific role
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $role = Role::findOrFail($id);
        $role->update(array_merge($validated, [
            'updated_by' => auth()->id(), // Assuming user authentication
        ]));

        return response()->json($role);
    }

    // Delete a specific role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
