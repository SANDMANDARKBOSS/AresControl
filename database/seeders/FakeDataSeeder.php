<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Measurement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Plan;

class FakeDataSeeder extends Seeder
{
    public function run()
    {
        // Check if there are Plans available
        $plans = Plan::all();
        if ($plans->isEmpty()) {
            $plans->push(Plan::create(['name' => 'Mensual Individual', 'description' => 'Acceso mensual', 'price' => 30.00]));
            $plans->push(Plan::create(['name' => 'Trimestral', 'description' => 'Acceso 3 meses', 'price' => 80.00]));
            $plans->push(Plan::create(['name' => 'Anual', 'description' => 'Acceso anual', 'price' => 280.00]));
        }

        $names = [
            'Carlos', 'Maria', 'Jose', 'Ana', 'Luis', 'Sofia', 'Miguel', 'Laura',
            'Diego', 'Valeria', 'Jorge', 'Daniela', 'Pedro', 'Camila', 'Andres'
        ];
        $lastNames = [
            'Perez', 'Gomez', 'Lopez', 'Diaz', 'Martinez', 'Rodriguez', 'Fernandez', 
            'Garcia', 'Sanchez', 'Romero', 'Suarez', 'Torres', 'Ramirez', 'Ruiz', 'Flores'
        ];

        for ($i = 0; $i < 50; $i++) {
            $name = $names[array_rand($names)];
            $lastName = $lastNames[array_rand($lastNames)];
            $idCard = '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $email = strtolower($name . '.' . $lastName . rand(1000, 99999) . '@ejemplo.com');

            // 1. Create User
            $user = User::create([
                'name' => $name,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role_id' => 1, // Client
            ]);

            // 2. Create Client
            $client = Client::create([
                'user_id' => $user->id,
                'name' => $name,
                'last_name' => $lastName,
                'id_card' => $idCard,
                'phone' => '09' . rand(10000000, 99999999),
                'entry_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'birth_date' => Carbon::now()->subYears(rand(18, 50))->subDays(rand(1, 365))->format('Y-m-d'),
            ]);

            // 3. Create Membership
            // 70% Active, 30% Inactive
            $isActive = rand(1000, 99999) <= 70;
            $plan = $plans->random();
            
            if ($isActive) {
                // About to expire in few days or normal active
                $daysLeft = rand(1, 30);
                $startDate = Carbon::now()->subDays(30 - $daysLeft)->format('Y-m-d');
                $endDate = Carbon::now()->addDays($daysLeft)->format('Y-m-d');
                $status = 'Activa';
            } else {
                $startDate = Carbon::now()->subMonths(2)->format('Y-m-d');
                $endDate = Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');
                $status = 'Vencida';
            }

            $membership = Membership::create([
                'plan_id' => $plan->id,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            $client->memberships()->attach($membership->id);

            // 4. Create Payments
            Payment::create([
                'membership_id' => $membership->id,
                'payment_method_id' => rand(1, 2), // Assuming 1=Efectivo, 2=Transferencia
                'amount' => $plan->price,
                'date' => Carbon::parse($startDate)->subDay()->format('Y-m-d'),
                'created_at' => Carbon::parse($startDate)->subDay(), // Paid 1 day before start
            ]);

            // 5. Create Measurements (Evolucion)
            // Generate 3-5 measurements over the last 6 months
            $numMeasurements = rand(3, 5);
            $baseWeight = rand(60, 100);
            
            for ($m = $numMeasurements; $m >= 1; $m--) {
                $mDate = Carbon::now()->subMonths($m)->addDays(rand(1, 10));
                // weight decreases slightly or fluctuates
                $weight = $baseWeight - ($numMeasurements - $m) * (rand(-5, 15) / 10); 
                
                Measurement::create([
                    'client_id' => $client->id,
                    'user_id' => 1, // Created by admin
                    'date' => $mDate->format('Y-m-d'),
                    'weight' => $weight,
                    'fat' => rand(15, 30) - ($numMeasurements - $m) * 0.5,
                    'muscle' => rand(30, 50) + ($numMeasurements - $m) * 0.2,
                    'created_at' => $mDate,
                ]);
            }
        }
    }
}
