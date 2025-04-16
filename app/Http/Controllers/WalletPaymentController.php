<?php

namespace App\Http\Controllers;

use App\Models\AddWallet;
use App\Models\WalletPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletPaymentController extends Controller
{
    public function fetchWallets()
    {
        $wallets = AddWallet::where('status', true)->get(['id', 'bank_name']);
        return response()->json($wallets);
    }

    // Fetch all wallet payments
    // public function index()
    // {
    //     $wallets = WalletPayment::with('wallet:id,bank_name')->get();
    //     return response()->json($wallets);
    // }

    public function index(Request $request)
    {
        $date = $request->query('date');

        $query = WalletPayment::query()->with('wallet:id,bank_name');

        if ($date) {
            $query->whereDate('date', $date);
        }

        $wallets = $query->get();

        return response()->json($wallets);
    }



//     public function index()
// {
//     $wallets = WalletPayment::with('wallet:id,bank_name')
//         ->whereDate('created_at', now())
//         ->get();

//     return response()->json($wallets);
// }

public function store(Request $request)
{
    $validated = $request->validate([
        'add_wallet_id' => 'required|exists:add_wallet,id',
        'number_of_trans' => 'required|integer|min:1',
        'amount' => 'required|numeric|min:0.01',
        'date' => 'nullable|date', // validate selectedDate
    ]);

    WalletPayment::create([
        'add_wallet_id' => $validated['add_wallet_id'],
        'number_of_trans' => $validated['number_of_trans'],
        'amount' => $validated['amount'],
        'date' => $validated['date'] ?? now()->toDateString(), // fallback to today if not passed
        'added_by' => Auth::id(),
    ]);

    return response()->json(['message' => 'Payment added successfully'], 201);
}


    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'add_wallet_id' => 'required|exists:add_wallet,id',
    //         'number_of_trans' => 'required|integer|min:1',
    //         'amount' => 'required|numeric|min:0.01',
    //     ]);

    //     WalletPayment::create([
    //         'add_wallet_id' => $validated['add_wallet_id'],
    //         'number_of_trans' => $validated['number_of_trans'],
    //         'amount' => $validated['amount'],
    //         'added_by' => Auth::id(),
    //     ]);

    //     return response()->json(['message' => 'Payment added successfully'], 201);
    // }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'add_wallet_id' => 'required|exists:add_wallet,id', // Ensure this matches your actual table name
            'number_of_trans' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payment = WalletPayment::findOrFail($id);

        $payment->update([
            'add_wallet_id' => $validated['add_wallet_id'],
            'number_of_trans' => $validated['number_of_trans'],
            'amount' => $validated['amount'],
            'updated_by' => Auth::id(), // Assuming 'updated_by' is a column in the table
        ]);

        return response()->json(['message' => 'Payment updated successfully'], 200);
    }

    public function fetchPayments()
    {
        $payments = WalletPayment::with('wallet:id,bank_name')->get();
        return response()->json($payments);
    }

    public function delete($id)
    {
        $payment = WalletPayment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }
}
