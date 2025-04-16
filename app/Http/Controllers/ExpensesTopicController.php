<?php

namespace App\Http\Controllers;

use App\Models\ExpensesTopic;
use Illuminate\Http\Request;

class ExpensesTopicController extends Controller
{

    public function index()
    {
        $topics = ExpensesTopic::all();
        return response()->json($topics);
    }
    public function updateStatus(Request $request, $id)
    {
        $expense = ExpensesTopic::findOrFail($id);
        $expense->status = $request->status;
        $expense->save();

        return response()->json(['success' => true, 'status' => $expense->status]);
    }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $topic = ExpensesTopic::create([
            'name' => $request->name,
            'added_by' => auth()->id(),
        ]);

        return response()->json($topic);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $topic = ExpensesTopic::findOrFail($id);
        $topic->update([
            'name' => $request->name,
            'updated_by' => auth()->id(),
        ]);

        return response()->json($topic);
    }

    public function destroy($id)
    {
        ExpensesTopic::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
