<?php

namespace App\Http\Controllers;

use App\Models\GivenLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GivenLoanController extends Controller
{
    // old
    // public function index()
    // {
    //     $handLoans = GivenLoan::with('client')
    //         ->whereDate('created_at', Carbon::today())
    //         ->get();

    //     return response()->json([
    //         'message' => 'Hand loans retrieved successfully.',
    //         'data' => $handLoans
    //     ]);
    // }
    public function index(Request $request)
    {
        $queryDate = $request->input('date');

        $handLoans = GivenLoan::with('client')
            ->when($queryDate, function ($query, $queryDate) {
                $query->whereDate('date', Carbon::parse($queryDate));
            })
            ->get();

        return response()->json([
            'message' => 'Hand loans retrieved successfully.',
            'data' => $handLoans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'client_id' => 'required|exists:clients,id',
    //         'voucher_type' => 'required|string|max:255',
    //         'amount' => 'required|numeric',
    //         'narration' => 'nullable|string|max:255',
    //         'amount_in_words' => 'required|string',
    //     ]);

    //     $validated['added_by'] = auth()->id(); // Assuming authenticated user
    //     $handLoan = GivenLoan::create($validated);

    //     return response()->json([
    //         'message' => 'Hand loan created successfully.',
    //         'data' => $handLoan
    //     ], 201);
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'voucher_type' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string|max:255',
            'amount_in_words' => 'required|string',
            'date' => 'required|date', // <-- Add this line
        ]);

        $validated['added_by'] = auth()->id();

        $handLoan = GivenLoan::create($validated);

        return response()->json([
            'message' => 'Hand loan created successfully.',
            'data' => $handLoan
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(GivenLoan $handLoan)
    {
        return response()->json([
            'message' => 'Hand loan retrieved successfully.',
            'data' => $handLoan
        ]);
    }

    public function update(Request $request, $id)
    {
        $handloan = GivenLoan::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'voucher_type' => 'required|in:Debit-out,Credit-In',
            'amount' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
        ]);

        $handloan->update($request->all());

        return response()->json(['data' => $handloan], 200);
    }

    public function destroy($id)
    {
        $handloan = GivenLoan::findOrFail($id);
        $handloan->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
