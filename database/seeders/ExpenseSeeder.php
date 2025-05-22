<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $user = User::find(1);
    
        if (!$user) {
            throw new \Exception("Usuário não encontrado!");
        }

        Expense::factory()
            ->count(10) 
            ->create([
                'user_id' => $user->id
            ]);
    }
}
