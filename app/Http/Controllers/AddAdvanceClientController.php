<?php

namespace App\Http\Controllers;

use App\Models\AddAdvanceClient;
use Illuminate\Http\Request;

class AddAdvanceClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = AddAdvanceClient::all();
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
        ]);

        $client = AddAdvanceClient::create([
            'client_name' => $request->client_name,
        ]);

        return response()->json($client, 201); // Return the created client as JSON
    }

    /**
     * Display the specified resource.
     */
    public function show(AddAdvanceClient $addAdvanceClient)
    {
        return response()->json($addAdvanceClient); // Return the client as JSON
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddAdvanceClient $addAdvanceClient)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
        ]);

        $addAdvanceClient->update([
            'client_name' => $request->client_name,
        ]);

        return response()->json($addAdvanceClient); // Return the updated client as JSON
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddAdvanceClient $addAdvanceClient)
    {
        $addAdvanceClient->delete();

        return response()->json(['message' => 'Client deleted successfully']); // Return a success message
    }
}
