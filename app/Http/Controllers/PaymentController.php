<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_holder_name' => 'required|string',
            'payment_name' => 'required|string',
            'payment_no' => 'required|string',
            'payment_start_date' => 'required|date',
            'payment_end_date' => 'required|date',
            'mode' => 'required|string',
            'date' => 'required|date',
            'payment_account' => 'required|string',
            'agent_name' => 'required|string',
            'contact_no' => 'required|string',
            'payment_model' => 'required|string',
            'payment_date' => 'required|date',
            'payment_amt' => 'required|numeric',
        ]);

        $payment = Payment::create($validated);

        return response()->json($payment, 201);
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json($payment);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'payment_holder_name' => 'string',
            'payment_name' => 'string',
            'payment_no' => 'string',
            'payment_start_date' => 'date',
            'payment_end_date' => 'date',
            'mode' => 'string',
            'date' => 'date',
            'payment_account' => 'string',
            'agent_name' => 'string',
            'contact_no' => 'string',
            'payment_model' => 'string',
            'payment_date' => 'date',
            'payment_amt' => 'numeric',
        ]);

        $payment->update($validated);

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment record deleted successfully.']);
    }
}
