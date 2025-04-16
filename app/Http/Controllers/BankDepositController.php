<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use Illuminate\Http\Request;
use App\Models\BankDeposit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BankDepositController extends Controller
{
    // public function getBanks()
    // {
    //     $banks = AddBankDeposit::select('id', 'bank_name','account_number','account_name')->get();
    //     return response()->json($banks);
    // }

    public function getBanks()
    {
        // Fetch only banks with status true
        $banks = AddBankDeposit::select('id', 'bank_name', 'account_number', 'account_name')
            ->where('status', false)
            ->get();

        return response()->json($banks);
    }


    public function getAccountNumber($bankId)
    {
        $account = AddBankDeposit::where('id', $bankId)->first();
        return response()->json($account ? $account->account_number : null);
    }

    /**
     * Fetch all bank deposits and related bank names.
     */
    // public function index()
    // {
    //     $deposits = BankDeposit::with('addBankDeposit')->get();
    //     return response()->json($deposits);
    // }
    public function index(Request $request)
    {
        $date = $request->query('date');

        $query = BankDeposit::query()->with('addBankDeposit');

        if ($date) {
            $query->whereDate('date', $date);
        }

        $deposits = $query->get();

        return response()->json($deposits);
    }
    // public function index()
    // {
    //     $deposits = BankDeposit::with('addBankDeposit')
    //         ->whereDate('created_at', Carbon::today()) // Fetch only today's records
    //         ->get();

    //     return response()->json($deposits);
    // }
    /**
     * Store a new bank deposit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
            'account_number' => 'nullable|string|max:255',
            'amount_words' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'nullable|date', // validate selectedDate
        ]);

        $deposit = BankDeposit::create([
            'add_bank_deposit_id' => $request->add_bank_deposit_id,
            'account_number' => $request->account_number,
            'amount_words' => $request->amount_words,
            'amount' => $request->amount,
            'date' => $request['date'] ?? now()->toDateString(), // fallback to today if not passed
            'added_by' => Auth::id(),
        ]);

        return response()->json($deposit, 201);
    }

    /**
     * Update an existing bank deposit.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
            'account_number' => 'nullable|string|max:255',
            'amount_words' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $deposit = BankDeposit::findOrFail($id);

        $deposit->update([
            'add_bank_deposit_id' => $request->add_bank_deposit_id,
            'account_number' => $request->account_number,
            'amount_words' => $request->amount_words,
            'amount' => $request->amount,
            'updated_by' => Auth::id(),
        ]);

        return response()->json($deposit);
    }

    /**
     * Delete a bank deposit.
     */
    public function destroy($id)
    {
        $deposit = BankDeposit::findOrFail($id);
        $deposit->delete();

        return response()->json(['message' => 'Bank deposit deleted successfully.']);
    }

    /**
     * Fetch all bank names (from AddBankDeposit).
     */
    public function fetchBanks()
    {
        $banks = AddBankDeposit::all(); // Assuming AddBankDeposit model exists
        return response()->json($banks);
    }
}
