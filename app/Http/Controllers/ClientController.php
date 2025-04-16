<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json([
            'message' => 'Clients retrieved successfully.',
            'data' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
        ]);
        $validated['added_by'] = auth()->id(); // Assuming authenticated user
        $validated['updated_by'] = auth()->id();
        $client = Client::create($validated);

        return response()->json([
            'message' => 'Client created successfully.',
            'data' => $client
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json([
            'message' => 'Client retrieved successfully.',
            'data' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'added_by' => 'required|integer',
            'updated_by' => 'nullable|integer',
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Client updated successfully.',
            'data' => $client
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully.'
        ]);
    }
}
