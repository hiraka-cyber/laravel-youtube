<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Movie::create([
                'user_id'    => $i,
                'text'       => 'これはテスト投稿' .$i,
                'image'  => 'https://placehold.jp/50x50.mp4',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
