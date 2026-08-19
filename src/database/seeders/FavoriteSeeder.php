<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;


class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUser = User::find(1);

        if ($testUser) {
            $testUser->favoriteItems()->syncWithoutDetaching([1]);
        }

        $items = Item::all();
        $users = User::where('id', '>=', 3)->get();

        foreach ($items as $item) {
            $count = min($users->count(), rand(2, 5));
            $randomUsers = $users->random($count);

            foreach ($randomUsers as $user) {
                $user->favoriteItems()->toggle($item->id);
            }
        }
    }
}
