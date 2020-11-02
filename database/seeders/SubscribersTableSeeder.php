<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2; $i <= 10; $i++) {
            Subscriber::create([
                'subscribing_id' => $i,
                'subscribed_id' => 1
            ]);
        }
    }
}
