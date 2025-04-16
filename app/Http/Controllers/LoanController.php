<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_holder_name' => 'required|string',
            'loan_name' => 'required|string',
            'loan_no' => 'required|string',
            'loan_start_date' => 'required|date',
            'loan_end_date' => 'required|date',
            'mode' => 'required|string',
            'date' => 'required|date',
            'loan_amt' => 'required|numeric',
            'agent_name' => 'required|string',
            'contact_number' => 'required|string',
            'payment_model' => 'required|string',
            'payment_date' => 'required|date',
            'payment_amt' => 'required|numeric',
        ]);

        $loan = Loan::create($validated);

        return response()->json($loan, 201);
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return response()->json($loan);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'loan_holder_name' => 'string',
            'loan_name' => 'string',
            'loan_no' => 'string',
            'loan_start_date' => 'date',
            'loan_end_date' => 'date',
            'mode' => 'string',
            'date' => 'date',
            'loan_amt' => 'numeric',
            'agent_name' => 'string',
            'contact_number' => 'string',
            'payment_model' => 'string',
            'payment_date' => 'date',
            'payment_amt' => 'numeric',
        ]);

        $loan->update($validated);

        return response()->json($loan);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Loan record deleted successfully.']);
    }
}
