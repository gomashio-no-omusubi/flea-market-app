<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //以下2件のみ各カラムを指定し、リアルなダミーデーターを作成※それ以外は下の指示に従い作成
        Comment::create([
            'item_id' => 1,
            'user_id' => 3,
            'content' => 'コメント失礼します。購入を検討しているのですが、お値下げは可能でしょうか？その場合、いくらまで可能でしょうか？'
        ]);

        Comment::create([
            'item_id' => 1,
            'user_id' => 2,
            'content' => 'コメントありがとうございます！今のところ値下げは考えておりません。',
        ]);

        //itemのID:1には上記でリアルな表示テストとして使用したいため、それ以外のものを$itemsとする
        $users = User::all();
        $items = Item::where('id', '!=', 1)->get();

        //出品者が商品を削除する可能性も踏まえ、商品一覧にあるものが5個以上であることを条件とする
        if ($items->count() > 5) {
            $items = $items->random(5);
        }

        //上記の条件をクリアしたitemに1～2つのコメントを載せる
        foreach ($items as $item) {
            Comment::factory(rand(1, 2))->create([
                'item_id' => $item->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
