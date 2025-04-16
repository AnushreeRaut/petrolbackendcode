<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddBankDepositController extends Controller
{
    // Fetch all bank deposits
    public function index()
    {
        $deposits = AddBankDeposit::all();
        return response()->json($deposits);
    }

    // Store a new bank deposit
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
        ]);

        $deposit = AddBankDeposit::create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_branch' => $request->bank_branch,
            'account_type' => $request->account_type,
            'added_by' => Auth::id(), // Authenticated user
        ]);

        return response()->json($deposit, 201);
    }

    // Show a specific bank deposit
    public function show($id)
    {
        $deposit = AddBankDeposit::find($id);

        if (!$deposit) {
            return response()->json(['message' => 'Bank deposit not found'], 404);
        }

        return response()->json($deposit);
    }

    // Update a specific bank deposit
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'account_type' => 'required|string|max:255',
        ]);

        $deposit = AddBankDeposit::find($id);

        if (!$deposit) {
            return response()->json(['message' => 'Bank deposit not found'], 404);
        }

        $deposit->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_branch' => $request->bank_branch,
            'account_type' => $request->account_type,
            'updated_by' => Auth::id(), // Authenticated user
        ]);

        return response()->json($deposit);
    }

    // Delete a specific bank deposit
    public function destroy($id)
    {
        $deposit = AddBankDeposit::find($id);

        if (!$deposit) {
            return response()->json(['message' => 'Bank deposit not found'], 404);
        }

        $deposit->delete();

        return response()->json(['message' => 'Bank deposit deleted successfully']);
    }

    public function updateStatusAndShow(Request $request, $id)
    {
        $deposit = AddBankDeposit::find($id);

        if (!$deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        // Toggle status
        if ($request->has('status')) {
            $deposit->status = $request->status;
        }

        // Toggle show
        if ($request->has('show')) {
            $deposit->show = $request->show;
        }

        $deposit->save();

        return response()->json($deposit);
    }
}
