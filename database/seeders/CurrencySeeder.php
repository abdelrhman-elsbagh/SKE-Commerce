<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        // Path to the currencies.json file
        $jsonPath = public_path('assets/currencies.json');

        // Check if the file exists
        if (!File::exists($jsonPath)) {
            $this->command->error("File not found: " . $jsonPath);
            return;
        }

        // Read the JSON file
        $json = File::get($jsonPath);
        $currencies = json_decode($json, true);

        if (is_null($currencies)) {
            $this->command->error("Invalid JSON format in file: " . $jsonPath);
            return;
        }

        // Loop through the currencies and insert them into the database
        foreach ($currencies as $code => $name) {
            Currency::updateOrCreate(
                ['currency' => $code],
                [
                    'name' => $name,
                    'price' => 1,
                    'status' => $code === 'USD' ? 'active' : 'inactive'
                ]
            );
        }

        $this->command->info("Currencies seeded successfully.");
    }
}
