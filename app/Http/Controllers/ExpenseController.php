<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpensesTopic;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {

        $expenses = Expense::with('topic')
        ->whereDate('created_at', now())
        ->get();
        $topics = ExpensesTopic::where('status', true)->get();
        return response()->json([
            'expenses' => $expenses,
            'topics' => $topics,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'expenses_id' => 'required|exists:expenses_topics,id',
            'amount' => 'required|numeric|min:0',
            'narration' => 'required|string',
        ]);

        $expense = Expense::create([
            'expenses_id' => $request->expenses_id,
            'amount' => $request->amount,
            'narration' => $request->narration,
            'added_by' => auth()->id(),
        ]);

        return response()->json($expense, 201);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'expenses_id' => 'required|exists:expenses_topics,id',
            'amount' => 'required|numeric|min:0',
            'narration' => 'required|string',
        ]);

        $expense->update([
            'expenses_id' => $request->expenses_id,
            'amount' => $request->amount,
            'narration' => $request->narration,
            'updated_by' => auth()->id(),
        ]);

        return response()->json($expense);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
