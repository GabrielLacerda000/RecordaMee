<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            id => 1,
            'name' => 'paid',
        ]);
        
        Status::create([
            id => 2,
            'name' => 'pending',
        ]);

        Status::create([
            id => 3,
            'name' => 'overdue',
        ]);
    }
}
