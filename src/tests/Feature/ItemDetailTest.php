<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Condition;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ConditionSeeder;


class ItemDetailTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 商品詳細情報取得
     * ========================================================================= */

    // 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）。複数選択されたカテゴリが表示されているか
    public function test_all_item_details_and_multiple_categories_are_displayed()
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'img_url' => 'assets/images/rolex.jpg',
            'name' => '限定メンズ高級腕時計',
            'brand' => 'ロレックス',
            'price' => 250000,
            'description' => '最高品質のラグジュアリーな腕時計です。',
            'condition_id' => $condition->id,
        ]);

        $category1 = Category::where('name', 'ファッション')->first();
        $category2 = Category::where('name', 'メンズ')->first();
        $item->categories()->attach([$category1->id, $category2->id]);

        $user->favoriteItems()->attach($item->id);

        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'この商品について質問です。'
        ]);

        $response = $this->get(route('items.show', ['item_id' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee($item->img_url);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($condition->name);

        $response->assertSee($category1->name);
        $response->assertSee($category2->name);

        $response->assertSeeInOrder([
            'いいね済',
            '1'
        ]);

        $response->assertSeeInOrder([
            'コメント',
            '1'
        ]);

        $response->assertSee($user->name);
        $response->assertSee($comment->content);
    }
}
