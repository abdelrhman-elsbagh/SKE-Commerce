<?php

namespace Database\Seeders;

use App\Models\TermsConditions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermsConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermsConditions::create([
            'data' => 'These are the terms and conditions.',
        ]);
    }
}
