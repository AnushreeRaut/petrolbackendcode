<?php

namespace App\Http\Controllers;

use App\Models\CreditClient;
use Illuminate\Http\Request;

class RoughController extends Controller
{
    // public function getCreditClients()
    // {  // Fetch all credit clients with related client credits and tanks
    //     $creditClients = CreditClient::with(['clientCredit', 'tank'])->get();

    //     return response()->json($creditClients);
    // }

    public function getAllCreditClients()
    {
        $creditClients = CreditClient::all(); // Fetch all records
        return response()->json($creditClients);
    }

    public function getCreditClients()
    {
        // Get today's date
        $today = now()->toDateString(); // Get today's date in 'Y-m-d' format

        // Fetch credit clients where the created_at date matches today's date
        $creditClients = CreditClient::with(['clientCredit', 'tank','vehicle'])
            ->whereDate('created_at', $today)  // Filter by today's date
            ->get();

        return response()->json($creditClients);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'add_client_credit_id' => 'required|exists:client_credits,id',
            'tank_id' => 'required|exists:tanks,id',
            'bill_no' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'rate' => 'required|numeric',
            'quantity_in_liter' => 'required|numeric',
            'amt_wrds' => 'required|string|max:255',
            'vehicle_no' => 'nullable|string|max:255',
        ]);

        $creditClient = CreditClient::findOrFail($id);
        $creditClient->update(array_merge($validated, ['updated_by' => auth()->id()]));

        return response()->json(['message' => 'Credit Client updated successfully', 'data' => $creditClient], 200);
    }
}
