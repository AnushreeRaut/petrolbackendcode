<?php

namespace App\Http\Controllers;

use App\Models\AddWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddWalletController extends Controller
{
    public function index()
    {
        $wallets = AddWallet::all();
        return response()->json($wallets);
    }
    public function updateStatus(Request $request, $id)
    {
        $wallet = AddWallet::findOrFail($id);
        $wallet->status = $request->status;
        $wallet->save();

        return response()->json(['success' => true, 'status' => $wallet->status]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $wallet = AddWallet::create([
            'bank_name' => $request->bank_name,
            'added_by' => Auth::id(),
        ]);

        return response()->json($wallet, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $wallet = AddWallet::findOrFail($id);
        $wallet->update([
            'bank_name' => $request->bank_name,
            'updated_by' => Auth::id(),
        ]);

        return response()->json($wallet);
    }

    public function destroy($id)
    {
        $wallet = AddWallet::findOrFail($id);
        $wallet->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
