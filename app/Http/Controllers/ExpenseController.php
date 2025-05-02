<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpensesTopic;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString()); // 👈 Get date from query or today if not sent

        $expenses = Expense::with('topic')
            ->whereDate('date', $date) // 👈 Filter by `date` column (not created_at)
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
            'narration' => 'nullable|string',
            'date' => 'required|date', // 👈 Validate date also
        ]);

        $expense = Expense::create([
            'expenses_id' => $request->expenses_id,
            'amount' => $request->amount,
            'narration' => $request->narration,
            'added_by' => auth()->id(),
            'date' => $request->date, // 👈 Store selectedDate
        ]);

        return response()->json($expense, 201);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'expenses_id' => 'required|exists:expenses_topics,id',
            'amount' => 'required|numeric|min:0',
            'narration' => 'nullable|string',
            'date' => 'required|date', // 👈 validate
        ]);

        $expense->update([
            'expenses_id' => $request->expenses_id,
            'amount' => $request->amount,
            'narration' => $request->narration,
            'updated_by' => auth()->id(),
            'date' => $request->date, // 👈 update date
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
