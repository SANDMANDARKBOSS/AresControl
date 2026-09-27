<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Plan Mensual Individual',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'price' => 30.00,
                'validity_days' => 30, // Mensual
            ],
            [
                'name' => 'Plan Mensual Grupal 2-3',
                'min_capacity' => 2,
                'max_capacity' => 3,
                'price' => 25.00,
                'validity_days' => 30, // Mensual
            ],
            [
                'name' => 'Plan Mensual Grupal 4+',
                'min_capacity' => 4,
                'max_capacity' => null, // 4 en adelante
                'price' => 20.00,
                'validity_days' => 30, // Mensual
            ],
            [
                'name' => 'Plan Trimestral',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'price' => 80.00,
                'validity_days' => 90, // 3 meses
            ],
            [
                'name' => 'Plan Semestral',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'price' => 150.00,
                'validity_days' => 180, // 6 meses
            ],
            [
                'name' => 'Plan Anual',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'price' => 300.00,
                'validity_days' => 365, // 12 meses
            ],
            [
                'name' => 'Valor Diario',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'price' => 2.00,
                'validity_days' => 1, // 1 día
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
