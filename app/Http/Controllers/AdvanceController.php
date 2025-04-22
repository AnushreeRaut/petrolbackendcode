<?php

namespace App\Http\Controllers;

use App\Models\AddAdvanceClient;
use App\Models\Advance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function getclient()
    {
        return response()->json(AddAdvanceClient::all());
    }

    // public function index()
    // {
    //     $advance = Advance::with('client') ->whereDate('created_at', now()) // Fetch only today's records
    //     ->get();
    //     return response()->json($advance);
    // }

    public function index(Request $request)
    {
        $queryDate = $request->input('date');

        $advances = Advance::with('client')
            ->when($queryDate, function ($query, $queryDate) {
                $query->whereDate('date', Carbon::parse($queryDate));
            })
            ->get();

        return response()->json($advances);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'add_advance_client_id' => 'required|exists:add_advance_client,id',
            'voucher_type' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
            'date' => 'required|date', // <-- ADD THIS
        ]);

        $advance = Advance::create($validated + ['added_by' => auth()->id()]);
        return response()->json($advance, 201);
    }


    public function update(Request $request, Advance $advance)
    {
        $validated = $request->validate([
            'add_advance_client_id' => 'required|exists:add_advance_client,id',
            'voucher_type' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
        ]);

        $advance->update($validated + ['updated_by' => auth()->id()]);
        return response()->json($advance);
    }

    public function destroy(Advance $advance)
    {
        $advance->delete();
        return response()->json(null, 204);
    }
}
