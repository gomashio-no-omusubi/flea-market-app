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
        //ログイン後すぐにリアルなアプリにするため、テストユーザー（ID:1）を呼び出し、商品（ID:1）にいいねをさせておく
        $testUser = User::find(1);

        if ($testUser) {
            $testUser->favoriteItems()->syncWithoutDetaching([1]);
        }

        //ユーザーID：3以降の人たちでダミーデータを作成（ID:1は上記の内容で固定、ID:2は出品者のため）
        $items = Item::all();
        $users = User::where('id', '>=', 3)->get();

        //上記でall()したアイテムを取り出し、ランダムユーザー2～5人分のいいねをつける※itemがないといいねを付けられないため、先に$itemsをバラす
        foreach ($items as $item) {
            $count = min($users->count(), rand(2, 5));
            $randomUsers = $users->random($count);

            foreach ($randomUsers as $user) {
                $user->favoriteItems()->toggle($item->id);
            }
        }
    }
}
