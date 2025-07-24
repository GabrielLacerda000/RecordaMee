<?php

namespace Database\Seeders;

use App\Models\Recurrence;
use Illuminate\Database\Seeder;

class RecurrenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Recurrence::create([
            id => 1,
            'name' => 'unique',
        ]);
        Recurrence::create([
            id => 2,
            'name' => 'daily',
        ]);
        Recurrence::create([
            id => 3,
            'name' => 'weekly',
        ]);
        Recurrence::create([
            id => 4,
            'name' => 'monthly',
        ]);
        Recurrence::create([
            id => 5,
            'name' => 'semester',
        ]);
        Recurrence::create([
            id => 6,
            'name' => 'yearly',
        ]);
    }
}
