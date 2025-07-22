<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Recurrence;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentDate = $this->faker->optional()->dateTimeThisYear?->format('Y-m-d');

        return [
            'name' => $this->faker->words(3, true),
            'due_date' => $this->faker->dateTimeThisYear->format('Y-m-d'),
            'status_id' => rand(1,2),
            'recurrence_id' => rand(1,4),
            'category_id' => rand(1,8),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'payment_date' => $paymentDate,
            'user_id' => User::factory(),
            'isPaid' => $paymentDate !== null,
        ];
    }
}
