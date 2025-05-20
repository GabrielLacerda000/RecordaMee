<?php

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseService {
    public function getExpenses() {
        return request()->user()->expenses()->get();
    }
    public function createExpense(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status_id' => 'required|integer|exists:statuses,id',
            'recurrence_id' => 'nullable|integer|exists:recurrences,id',
            'category_id' => 'required|integer|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
        ]);

        return $request->user()->expenses()->create($validated);
    }
    public function updateExpense($id, Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status_id' => 'required|integer|exists:statuses,id',
            'recurrence_id' => 'nullable|integer|exists:recurrences,id',
            'category_id' => 'required|integer|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
        ]);
        $expense = Expense::findOrFail($id);
        $expense->update($validated);
        return $expense;
    }
    public function showExpense($id) {
        $expense = Expense::with('user')->findOrFail($id);
        if ($expense->user_id !== request()->user()->id) {
            abort(403, 'Acesso não autorizado à despesa.');
        }
        return $expense;
    }
    public function deleteExpense($id) {
         $expense = Expense::findOrFail($id);

         if ($expense->user_id !== request()->user()->id) {
            abort(403, 'Acesso não autorizado à despesa.');
        }

         return $expense->delete();
    }
}