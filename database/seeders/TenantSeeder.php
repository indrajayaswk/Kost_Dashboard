<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use Faker\Factory as Faker;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create(); // Initialize Faker for generating fake data

        // Create 10 sample tenants (adjust the number as needed)
        for ($i = 0; $i < 10; $i++) {
            Tenant::create([
                'name' => $faker->name, // Random name
                'phone' => '6281239366793', // Use the same phone number for all tenants
                'ktp' => $faker->unique()->numberBetween(1000000000000000, 9999999999999999), // Random KTP number
                'dp' => $faker->numberBetween(500000, 5000000), // Random down payment (dp)
                'start_date' => now(), // Random start date (current date)
                'end_date' => null, // Leave end_date as null
                'note' => $faker->sentence, // Random note (sentence)
                'created_at' => now(), // Set created_at to current date
            ]);
        }
    }
}
