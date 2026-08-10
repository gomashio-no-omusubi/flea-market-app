<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('public')->deleteDirectory('profile');
        Storage::disk('public')->deleteDirectory('items');

        Storage::disk('public')->makeDirectory('profile');
        Storage::disk('public')->makeDirectory('items');

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ConditionSeeder::class,
            ItemSeeder::class,
            FavoriteSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
