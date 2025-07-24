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
        $expense = $event->expense;
        
        $status =  Status::where('name', 'pending')->first()->id;
        $recurrenceUnique = Recurrence::where('name', 'unique')->first()->id;

        if ($expense->recurrence_id !== $recurrenceUnique) {
            $newDueDate = Carbon::parse($expense->due_date)->addMonth();

            Expense::create([
                'name' => $expense->name,
                'due_date' => $newDueDate,
                'status_id' => Status::where('name', 'pending')->first()->id,
                'recurrence_id' => $expense->recurrence_id,
                'category_id' => $expense->category_id,
                'amount' => $expense->amount,
                'user_id' => $expense->user_id,
            ]);
        }
    }
}
