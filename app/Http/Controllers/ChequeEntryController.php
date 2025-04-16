<?php

namespace App\Http\Controllers;

use App\Models\ChequeEntry;
use App\Models\ClientCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChequeEntryController extends Controller
{

    public function getClients()
    {
        $clients = ClientCredit::all(['id', 'client_name']); // Only fetch the id and client_name
        return response()->json($clients);

    }
    // getClientsdata
    public function getClientsdata()
    {
        $clients = ClientCredit::all(['id', 'client_name']); // Only fetch the id and client_name
        return response()->json($clients);

    }
    public function index()
    {
        $cheques = ChequeEntry::with('clientCredit')->get();
        return response()->json($cheques);
    }

    public function store(Request $request)
    {
        $request->validate([
            'add_client_credit_id' => 'required|exists:client_credits,id',
            'bill_no' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'bill_amt' => 'required|numeric|min:0',
            'cheque_no' => 'required|string|max:255|unique:cheque_entries,cheque_no',
            'amount' => 'required|numeric|min:0',
            'cheque_date' => 'required|date',
            'bank_name' => 'required|string|max:255',
        ]);

        $cheque = ChequeEntry::create([
            'add_client_credit_id' => $request->add_client_credit_id,
            'bill_no' => $request->bill_no,
            'bill_date' => $request->bill_date,
            'bill_amt' => $request->bill_amt,
            'cheque_no' => $request->cheque_no,
            'amount' => $request->amount,
            'cheque_date' => $request->cheque_date,
            'bank_name' => $request->bank_name,
            'status' => 'pending',
            'added_by' => Auth::id(),
        ]);

        return response()->json($cheque, 201);
    }

    public function update(Request $request, $id)
    {
        $cheque = ChequeEntry::findOrFail($id);

        $request->validate([
            'add_client_credit_id' => 'required|exists:client_credits,id',
            'bill_no' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'bill_amt' => 'required|numeric|min:0',
            'cheque_no' => 'required|string|max:255|unique:cheque_entries,cheque_no,' . $cheque->id,
            'amount' => 'required|numeric|min:0',
            'cheque_date' => 'required|date',
            'bank_name' => 'required|string|max:255',
        ]);

        $cheque->update([
            'add_client_credit_id' => $request->add_client_credit_id,
            'bill_no' => $request->bill_no,
            'bill_date' => $request->bill_date,
            'bill_amt' => $request->bill_amt,
            'cheque_no' => $request->cheque_no,
            'amount' => $request->amount,
            'cheque_date' => $request->cheque_date,
            'bank_name' => $request->bank_name,
            'updated_by' => Auth::id(),
        ]);

        return response()->json($cheque);
    }

    public function destroy($id)
    {
        $cheque = ChequeEntry::findOrFail($id);
        $cheque->delete();
        return response()->json(['message' => 'Cheque deleted successfully']);
    }
}
