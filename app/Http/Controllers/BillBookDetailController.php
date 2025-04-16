<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillBookDetail;

class BillBookDetailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'credit_clients' => 'required|array', // Expect an array of client IDs
            'credit_clients.*' => 'exists:credit_clients,id', // Validate each entry
            'date' => 'nullable|date',
            'mode' => 'nullable|string',
            'account' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        $billDetails = [];
        foreach ($request->credit_clients as $clientId) {
            $billDetails[] = BillBookDetail::create([
                'credit_client_id' => $clientId,
                'date' => $request->date,
                'mode' => $request->mode,
                'account' => $request->account,
                'amount' => $request->amount,
                'remark' => $request->remark,
            ]);
        }

        return response()->json([
            'message' => 'Bill book details saved successfully!',
            'data' => $billDetails
        ], 201);
    }

    public function index()
    {
        $billBookDetails = BillBookDetail::all()
            ->groupBy('credit_client_id')
            ->map(function ($details, $creditClientId) {
                return [
                    'credit_client_id' => $creditClientId, // Grouped by credit_client_id
                    'total_amount' => $details->sum('amount'), // Summing the amount for grouped entries
                    'records' => $details->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'date' => $detail->date,
                            'mode' => $detail->mode,
                            'account' => $detail->account,
                            'amount' => $detail->amount,
                            'remark' => $detail->remark,
                        ];
                    })->values()
                ];
            })
            ->values();

        return response()->json($billBookDetails);
    }


}
