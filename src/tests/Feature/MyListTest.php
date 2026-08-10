<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Database\Seeders\ConditionSeeder;

class MyListTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * マイリスト一覧取得
     * ========================================================================= */

    // いいねした商品だけが表示される
    public function test_only_favorited_items_are_displayed()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $condition = Condition::first();

        $favItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => 'いいねした商品',
        ]);
        $otherItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => '通常の商品',
        ]);

        $user->favoriteItems()->attach($favItem->id);

        $response = $this->get(route('items.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee($favItem->name);
        $response->assertDontSee($otherItem->name);
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_out_item_in_mylist_displays_sold_label()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $condition = Condition::first();

        $availableItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => '販売中の商品',
        ]);
        $user->favoriteItems()->attach($availableItem->id);

        $soldItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => '購入済の商品',
        ]);
        $user->favoriteItems()->attach($soldItem->id);

        Purchase::factory()->create([
            'item_id' => $soldItem->id,
            'user_id' => User::factory(),
            'payment_method' => 'コンビニ支払い',
        ]);

        $response = $this->get(route('items.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    // 未認証の場合は何も表示されない
    public function test_guest_cannot_see_items_in_mylist()
    {
        $this->seed(ConditionSeeder::class);

        $condition = Condition::first();

        $item = Item::factory()->create([
            'name' => '未認証テスト用のダミー商品名',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get(route('items.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertDontSee($item->name);
    }
}
