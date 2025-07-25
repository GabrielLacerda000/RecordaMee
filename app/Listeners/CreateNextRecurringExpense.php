<?php

namespace App\Listeners;

use App\Events\ExpensePaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Status;
use App\Models\Recurrence;

use App\Models\Expense;
use Carbon\Carbon;

class CreateNextRecurringExpense implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ExpensePaid $event): void
    {
        $paidExpense = $event->expense;
        $uniqueRecurrence = Recurrence::where('name', 'unique')->first();

        if ($paidExpense->recurrence_id) {
            $recurrence = Recurrence::find($paidExpense->recurrence_id);
            
            if (!$recurrence) {
                return;
            }

            if (strtolower($recurrence->name) === 'unique') {
                return;
            }

            $newDueDate = Carbon::parse($paidExpense->due_date);

            switch (strtolower($recurrence->name)) {
                case 'daily':
                    $newDueDate->addDay();
                    break;
                case 'weekly':
                    $newDueDate->addWeek();
                    break;
                case 'monthly':
                    $newDueDate->addMonth();
                    break;
                case 'semester':
                    $newDueDate->addMonths(6);
                    break;
                case 'yearly':
                    $newDueDate->addYear();
                    break;
                default:
                    $newDueDate->addMonth();
                    break;
            }

            Expense::create([
                'name' => $paidExpense->name,
                'due_date' => $newDueDate,
                'status_id' => Status::where('name', 'pending')->first()->id,
                'recurrence_id' => $paidExpense->recurrence_id,
                'category_id' => $paidExpense->category_id,
                'amount' => $paidExpense->amount,
                'user_id' => $paidExpense->user_id,
                'parent_expense_id' => $paidExpense->id,
            ]);
        }
    }
}
