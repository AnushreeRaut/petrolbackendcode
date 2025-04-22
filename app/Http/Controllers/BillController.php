<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\ClientCredit;
use App\Models\CreditClient;
use Carbon\Carbon;

class BillController extends Controller
{


    public function getClients()
    {
        $clients = CreditClient::with(['tank', 'clientCredit'])->get();

        return response()->json($clients);
    }


    // public function index()
    // {
    //     $bills = Bill::with(['clientCredit.creditClients', 'clientCredit.creditClients.vehicle', 'clientCredit.creditClients.tank'])->get();
    //     return response()->json($bills);
    // }

    // public function index()
    // {
    //     $bills = Bill::with(['creditClient.vehicle', 'creditClient.tank','creditClient.clientCredit'])   ->whereDate('created_at', Carbon::today()) // Fetch only today's records
    //     ->get();
    //     return response()->json($bills);
    // }
    public function index()
{
    $bills = Bill::with([
        'creditClient' => function ($query) {
            $query->whereDate('created_at', Carbon::today());
        },
        'creditClient.vehicle',
        'creditClient.tank',
        'creditClient.clientCredit'
    ])
    ->whereDate('created_at', Carbon::today()) // Fetch only today's bills
    ->get()
    ->filter(function ($bill) {
        return $bill->creditClient !== null; // Remove bills with null creditClient
    })
    ->values(); // Reset array keys

    return response()->json($bills);
}




    // Store a new bill
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'credit_client_id' => 'required|exists:credit_clients,id',
    //         'bill_no' => 'required|string',
    //         'date' => 'required|date',
    //         'billing_date' => 'required|date',
    //     ]);

    //     $bill = Bill::create($request->all());
    //     return response()->json($bill, 201);
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'credit_client_ids' => 'required|array',
    //         'bill_no' => 'required|string',
    //         'date' => 'required|date',
    //         'billing_date' => 'required|date',
    //     ]);

    //     foreach ($request->credit_client_ids as $creditClientId) {
    //         Bill::create([
    //             'credit_client_id' => $creditClientId,
    //             'bill_no' => $request->bill_no,
    //             'date' => $request->date,
    //             'billing_date' => $request->billing_date,
    //         ]);
    //     }

    //     return response()->json(['message' => 'Bills generated successfully'], 201);
    // }
    public function store(Request $request)
    {
        $request->validate([
            'credit_client_ids' => 'required|array',
            'bill_no' => 'required|string',
            'date' => 'required|date',
            'billing_date' => 'required|date',
            // Optional: validate date_store if needed
            'date_store' => 'nullable|date',
        ]);

        foreach ($request->credit_client_ids as $creditClientId) {
            Bill::create([
                'credit_client_id' => $creditClientId,
                'bill_no' => $request->bill_no,
                'date' => $request->date,
                'billing_date' => $request->billing_date,
                'date_store' => $request->date_store ?? $request->date, // fallback to date if needed
            ]);
        }

        return response()->json(['message' => 'Bills generated successfully'], 201);
    }


    // Delete a bill
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();
        return response()->json(['message' => 'Bill deleted successfully']);
    }
}
