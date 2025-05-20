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
            'name' => 'Monthly',
        ]);
        Recurrence::create([
            'name' => 'Bianual',
        ]);
        Recurrence::create([
            'name' => 'semester',
        ]);
        Recurrence::create([
            'name' => 'Yearly',
        ]);
    }
}
