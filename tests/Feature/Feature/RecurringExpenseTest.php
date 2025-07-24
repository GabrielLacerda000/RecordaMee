<?php

use App\Models\User;
use App\Models\Expense;
use App\Models\Status;
use App\Models\Recurrence;
use App\Models\Category;
use App\Events\ExpensePaid;
use App\Listeners\CreateNextRecurringExpense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

it('should create a new recurring expense when a recurring expense is paid', function () {
    // Arrange
    Event::fake();

    $user = User::factory()->create();
    $statusPaid = Status::factory()->create(['name' => 'paid']);
    $statusPending = Status::factory()->create(['name' => 'pending']);
    $recurrence = Recurrence::factory()->create();
    $category = Category::factory()->create();

    $expense = Expense::factory()->create([
        'user_id' => $user->id,
        'status_id' => $statusPending->id,
        'recurrence_id' => $recurrence->id,
        'category_id' => $category->id,
        'due_date' => now()->toDateString(),
    ]);

    // Act
    actingAs($user)
        ->putJson("/api/expenses/update/{$expense->id}", [
            'status_id' => $statusPaid->id,
        ]);

    // Manually trigger the listener
    $event = new ExpensePaid($expense->fresh());
    $listener = new CreateNextRecurringExpense();
    $listener->handle($event);

    // Assert
    $this->assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'status_id' => $statusPaid->id,
    ]);

    $this->assertDatabaseHas('expenses', [
        'name' => $expense->name,
        'user_id' => $user->id,
        'status_id' => $statusPending->id,
        'recurrence_id' => $recurrence->id,
        'category_id' => $category->id,
        'due_date' => now()->addMonth()->startOfDay()->toDateTimeString(),
    ]);
});
