<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Feature;

class PlanSeeder extends Seeder
{
    public function run()
    {
        // Plan data
        $plans = [
            [
                'name' => 'Casual Gamer Plan',
                'price' => 10,
                'duration' => 30,
                'description' => 'This plan is perfect for casual gamers who want access to a variety of games without any additional perks.',
                'features' => [
                    'Access to 10 games',
                    'Basic customer support',
                    'Monthly game updates',
                    'Access to community forums',
                    '5GB cloud storage for saves',
                    'Standard game streaming quality',
                    'Basic achievements tracking'
                ]
            ],
            [
                'name' => 'Regular Gamer Plan',
                'price' => 25,
                'duration' => 30,
                'description' => 'The Regular Gamer Plan is ideal for gamers who play more frequently and want additional benefits.',
                'features' => [
                    'Access to 50 games',
                    'Priority customer support',
                    'Bi-weekly game updates',
                    'Access to exclusive community events',
                    '50GB cloud storage for saves',
                    'High-definition game streaming',
                    'Enhanced achievements and rewards'
                ]
            ],
            [
                'name' => 'Pro Gamer Plan',
                'price' => 50,
                'duration' => 30,
                'description' => 'The Pro Gamer Plan is designed for dedicated gamers who want the best experience and exclusive perks.',
                'features' => [
                    'Access to 200+ games',
                    '24/7 premium customer support',
                    'Weekly game updates',
                    'Access to beta games and early releases',
                    '100GB cloud storage for saves',
                    'Ultra high-definition game streaming',
                    'Exclusive in-game items and rewards'
                ]
            ]
        ];

        // Insert plans and their features
        foreach ($plans as $planData) {
            $plan = Plan::create([
                'name' => $planData['name'],
                'price' => $planData['price'],
                'duration' => $planData['duration'],
                'description' => $planData['description'],
            ]);

            foreach ($planData['features'] as $featureName) {
                $feature = Feature::firstOrCreate(['name' => $featureName]);
                $plan->features()->attach($feature);
            }
        }
    }
}
