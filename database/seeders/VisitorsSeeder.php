<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class VisitorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $cities = ['rampur', 'juannpur', 'meeret', 'lucknow'];
        $vcities = ['delhi','rampur', 'juannpur', 'meeret', 'lucknow','Noida']; // Post cities
        $userTypes = ['user', 'google_bot', 'facebook_bot', 'bing_bot']; // User types

        for ($i = 0; $i < 2500; $i++) {
            DB::table('visitors')->insert([
                'post_id' => $faker->numberBetween(11400, 11500), // Post ID between 11400 and 11500
                'post_city' => $faker->randomElement($cities),  // Random city
                'ip_address' => $faker->ipv4(),  // Random IP address
                'latitude' => $faker->latitude(),
                'longitude' => $faker->longitude(),
                'language' => $faker->languageCode(),
                'screen_width' => $faker->numberBetween(800, 1920), // Random screen width
                'screen_height' => $faker->numberBetween(600, 1080), // Random screen height
                'visit_count' => $faker->numberBetween(1, 10),  // Random visit count
                'visitor_city' => $faker->randomElement($vcities),
                'visitor_address' => $faker->address(),
                'user_agent' => $faker->userAgent(),
                'current_url' => $faker->url(),
                'referrer' => $faker->url(),
                'duration' => $faker->numberBetween(10, 300),  // Random duration in seconds
                'scroll' => $faker->numberBetween(10, 100) . '%',  // Random scroll percentage
                'user_type' => $faker->randomElement($userTypes),  // Random user type (Bot or User)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
