<?php

namespace App\Http\Controllers;

use App\Models\AddBankDeposit;
use App\Models\AddCard;
use App\Models\BankDepositAddCard;
use Illuminate\Http\Request;

class BankDepositAddCardController extends Controller
{
    public function index()
    {
        return response()->json(BankDepositAddCard::with(['bankDeposit', 'addCard'])->get());
    }

    public function fetchBankDeposits()
    {
        return response()->json(AddBankDeposit::all());
    }

    public function fetchAddCards()
    {
        return response()->json(AddCard::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'add_bank_deposit_id' => 'required|exists:add_bank_deposits,id',
            'add_card_id' => 'required|exists:add_cards,id',
            'tid_no' => 'required|integer',
            'narration' => 'required|string',
        ]);

        BankDepositAddCard::create($request->all());

        return response()->json(['message' => 'Data saved successfully']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'add_bank_deposit_id' => 'required',
            'add_card_id' => 'required',
            'tid_no' => 'required',
            'narration' => 'required',
        ]);

        $record = BankDepositAddCard::findOrFail($id);
        $record->update($validatedData);

        return response()->json(['message' => 'Record updated successfully']);
    }


    public function destroy($id)
{
    try {
        $record = BankDepositAddCard::findOrFail($id);
        $record->delete(); // Hard delete
        return response()->json(['message' => 'Record deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error deleting record'], 500);
    }
}

 // Toggle active/inactive status
 public function toggleStatus($id)
 {
     $record = BankDepositAddCard::findOrFail($id);
     $record->status = !$record->status;
     $record->save();

     return response()->json(['message' => 'Status updated successfully', 'status' => $record->status]);
 }
}
