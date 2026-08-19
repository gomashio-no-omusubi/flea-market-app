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

        $users = User::all();
        $items = Item::where('id', '!=', 1)->get();

        if ($items->count() > 5) {
            $items = $items->random(5);
        }

        foreach ($items as $item) {
            Comment::factory(rand(1, 2))->create([
                'item_id' => $item->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
