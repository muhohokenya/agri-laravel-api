<?php

namespace Database\Seeders;

use App\Models\interest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interest::factory(10)->create();
         \App\Models\Interest::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
         ]);
    }
}
