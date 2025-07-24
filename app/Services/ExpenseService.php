<?php
namespace App\Services;

use App\Models\Expense;
use App\Models\Recurrence;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Events\ExpensePaid;

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

    $expense = $request->user()->expenses()->create($validated);

    $statusPaid = Status::where('name', 'paid')->first();

    if ($expense->status_id === $statusPaid->id) {
        ExpensePaid::dispatch($expense);
    }

    return $expense->load(['category:id,name', 'status:id,name', 'recurrence:id,name']);
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
            'isPaid' => 'sometimes|boolean',
        ]);

        $expense = Expense::find($id);

        if(!$expense) return null;

        $statusPaid = Status::where('name', 'paid')->first();
        $originalStatusId = $expense->getOriginal('status_id');

        $expense->fill($validated)->save();

        if ($expense->status_id === $statusPaid->id && $originalStatusId !== $statusPaid->id) {
            ExpensePaid::dispatch($expense);
        }

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

        $statusPaid = Status::where('name', 'paid')->first();
        $statusPending = Status::where('name', 'pending')->first();
        $statusOverdue = Status::where('name', 'overdue')->first();

        $total = $user->expenses()->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00');
        
        $totalPaid = $statusPaid
            ? $user->expenses()->where('status_id', $statusPaid->id)->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00')
            : '0.00';
        
        $totalPending = $statusPending
            ? $user->expenses()->where('status_id', $statusPending->id)->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00')
            : '0.00';

        $totalOverdue = $statusPending
            ? $user->expenses()->where('status_id', $statusOverdue->id)->pluck('amount')->reduce(fn($carry, $item) => bcadd($carry, $item, 2), '0.00')
            : '0.00';
        
        return [
            'total' => $total,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'totalOverdue' => $totalOverdue,
        ];
        
    }

    public function getNextRecurrencyExpenses() {
        $user = request()->user();
        $statusPaid = Status::where('name', 'paid')->first();
        $recurrenceUnique = Recurrence::where('name', 'unique')->first();

        if (!$statusPaid || !$recurrenceUnique) {
            return null; 
        }

        $nextExpenses = $user->expenses()
            ->where('recurrence_id', '!=', $recurrenceUnique->id)
            ->where('status_id', '!=', $statusPaid->id)
            ->where('due_date', '>=', now()->toDateString())
            ->orderBy('due_date')
            ->get()
            ->groupBy('recurrence_id')
            ->map(function ($expenses) {
                return $expenses->first();
            });

        return $nextExpenses->values();
    }
}