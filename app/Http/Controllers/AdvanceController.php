<?php

namespace App\Http\Controllers;

use App\Models\AddAdvanceClient;
use App\Models\Advance;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function getclient()
    {
        return response()->json(AddAdvanceClient::all());
    }

    public function index()
    {
        $advance = Advance::with('client') ->whereDate('created_at', now()) // Fetch only today's records
        ->get();
        return response()->json($advance);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'add_advance_client_id' => 'required|exists:add_advance_client,id',
            'voucher_type' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
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
