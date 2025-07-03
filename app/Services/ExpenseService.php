<?php
namespace App\Services;

use App\Models\Expense;
use App\Models\Status;
use Illuminate\Http\Request;

class ExpenseService {
    public function getExpenses() {
        return request()->user()->expenses()->with([
            'category:id,name',
            'status:id,name',
            'recurrence:id,name'
        ])->get();
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
            'name' => 'sometimes|string|max:255',
            'due_date' => 'sometimes|date',
            'status_id' => 'sometimes|integer|exists:statuses,id',
            'recurrence_id' => 'nullable|integer|exists:recurrences,id',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'amount' => 'sometimes|numeric|min:0',
            'payment_date' => 'nullable|date',
        ]);

        $expense = Expense::find($id);

        if(!$expense) return null;

        $expense->fill($validated)->save();
        return $expense;
    }
    public function showExpense($id) {
        $expense = Expense::with([
            'category:id,name',
            'status:id,name',
            'recurrence:id,name'
        ])->where('id', $id)->first();

        if(!$expense) return null;
        
        if ($expense->user_id !== request()->user()->id) {
            abort(403, 'Acesso não autorizado à despesa.');
        }
        return $expense;
    }
    public function deleteExpense($id) {
         $expense = Expense::find($id);

         if(!$expense) return null;

         if ($expense->user_id !== request()->user()->id) {
            abort(403, 'Acesso não autorizado à despesa.');
        }

         return $expense->delete();
    }
    public function getExpensesSummary() {
        $user = request()->user();

        $statusPaid = Status::where('name', 'Paid')->first();
        $statusPending = Status::where('name', 'Pending')->first();

        $total = $user->expenses()->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00');
        
        $totalPaid = $statusPaid
            ? $user->expenses()->where('status_id', $statusPaid->id)->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00')
            : '0.00';
        
        $totalPending = $statusPending
            ? $user->expenses()->where('status_id', $statusPending->id)->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00')
            : '0.00';
        
        return [
            'total' => $total,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
        ];
        
    }
}