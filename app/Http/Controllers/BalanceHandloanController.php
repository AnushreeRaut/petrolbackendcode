<?php

namespace App\Http\Controllers;

use App\Models\BalanceHandloan;
use App\Models\HandLoan;
use Illuminate\Http\Request;

class BalanceHandloanController extends Controller
{


    // public function fetchHandloanClient()
    // {
    //     $handloansWithClients = HandLoan::with('client')
    //         ->whereHas('client')
    //         ->selectRaw('client_id, GROUP_CONCAT(id) as hand_loan_ids, SUM(amount) as total_amount')
    //         ->groupBy('client_id')
    //         ->get();

    //     $handloansWithClients->transform(function ($handloan) {
    //         return [
    //             'client_id' => $handloan->client_id, // Client ID
    //             'client_name' => $handloan->client->client_name, // Client Name
    //             'hand_loan_ids' => explode(',', $handloan->hand_loan_ids), // Convert comma-separated IDs to array
    //             'total_amount' => $handloan->total_amount // Total Handloan Amount
    //         ];
    //     });

    //     return response()->json($handloansWithClients);
    // }
    public function fetchHandloanClient()
    {
        $clientsWithHandloans = HandLoan::with('client')
            ->whereHas('client')
            ->get()
            ->groupBy('client_id')
            ->map(function ($loans) {
                return [
                    'client_id' => $loans->first()->client_id,
                    'client_name' => $loans->first()->client->client_name,
                    'handloans' => $loans->map(function ($loan) {
                        return [
                            'id' => $loan->id,
                            'amount' => $loan->amount,
                            'narration' => $loan->narration
                        ];
                    })->values()
                ];
            })
            ->values();

        return response()->json($clientsWithHandloans);
    }

//     public function fetchHandloanClient()
// {
//     $handloansWithClients = HandLoan::with('client')
//         ->whereHas('client')
//         ->get();

//     $handloansWithClients->transform(function ($handloan) {
//         return [
//             'id' => $handloan->id, // Handloan ID
//             'client_id' => $handloan->client_id, // Client ID
//             'client_name' => $handloan->client->client_name, // Client Name
//             'amount' => $handloan->amount, // Handloan Amount
//             'narration' => $handloan->narration, // Handloan Narration
//             'created_at' => $handloan->created_at->format('Y-m-d H:i:s') // Optional date formatting
//         ];
//     });

//     return response()->json($handloansWithClients);
// }


    public function index()
    {
        $balanceHandloans = BalanceHandloan::with('handLoan.client')->get();
        return response()->json($balanceHandloans);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'hand_loans_id' => 'required|exists:hand_loans,id',
            'voucher_type' => 'required|string|max:255',
            'cr_amount' => 'nullable|numeric',
            'dr_amount' => 'nullable|numeric',
            'bal_amount' => 'nullable|numeric',
            'narration' => 'nullable|string|max:500',
        ]);

        try {
            // Create a new BalanceHandloan record
            $balanceHandloan = BalanceHandloan::create([
                'hand_loans_id' => $request->hand_loans_id,
                'voucher_type' => $request->voucher_type,
                'cr_amount' => $request->cr_amount,
                'dr_amount' => $request->dr_amount,
                'bal_amount' => $request->bal_amount,
                'narration' => $request->narration,
            ]);

            return response()->json(['message' => 'Handloan record saved successfully!', 'data' => $balanceHandloan], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save handloan record', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(BalanceHandloan $balanceHandloan)
    {
        return response()->json($balanceHandloan);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'hand_loans_id' => 'required|exists:hand_loans,id',
            'voucher_type' => 'required|string|max:255',
            'cr_amount' => 'nullable|numeric',
            'dr_amount' => 'nullable|numeric',
            'bal_amount' => 'nullable|numeric',
            'narration' => 'nullable|string|max:500',
        ]);

        try {
            // Find the existing BalanceHandloan record
            $balanceHandloan = BalanceHandloan::findOrFail($id);

            // Update the record
            $balanceHandloan->update([
                'hand_loans_id' => $request->hand_loans_id,
                'voucher_type' => $request->voucher_type,
                'cr_amount' => $request->cr_amount,
                'dr_amount' => $request->dr_amount,
                'bal_amount' => $request->bal_amount,
                'narration' => $request->narration,
            ]);

            return response()->json(['message' => 'Handloan record updated successfully!', 'data' => $balanceHandloan], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update handloan record', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy(BalanceHandloan $balanceHandloan)
    {
        $balanceHandloan->delete();
        return response()->json(['message' => 'Record deleted successfully.'], 200);
    }

    public function getClientBalance($clientId)
{
    $balance = BalanceHandloan::whereHas('handLoan', function ($query) use ($clientId) {
        $query->where('client_id', $clientId);
    })->sum('bal_amount');

    return response()->json(['bal_amount' => $balance]);
}

}
