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
            'name' => 'daily',
        ]);
        Recurrence::create([
            'name' => 'weekly',
        ]);
        Recurrence::create([
            'name' => 'monthly',
        ]);
        Recurrence::create([
            'name' => 'bianual',
        ]);
        Recurrence::create([
            'name' => 'semester',
        ]);
        Recurrence::create([
            'name' => 'yearly',
        ]);
    }
}
