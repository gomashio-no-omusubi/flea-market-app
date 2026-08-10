<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class ItemIndexTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 商品一覧取得
     * ========================================================================= */

    // 全商品を取得できる
    public function test_user_can_view_all_items()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $condition = Condition::first();

        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品A',
        ]);
        $item2 = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品B',
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertSee($item1->name);
        $response->assertSee($item2->name);
    }


    // 購入済み商品は「Sold」と表示される
    public function test_sold_out_item_displays_sold_label()
    {
        $this->seed(ConditionSeeder::class);

        $condition = Condition::first();
        $soldItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => '売り切れ商品',
        ]);

        Purchase::factory()->create([
            'item_id' => $soldItem->id,
            'user_id' => User::factory(),
            'payment_method' => 'コンビニ支払い',
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertSee($soldItem->name);
        $response->assertSee('Sold');
    }

    // 自分が出品した商品は表示されない
    public function test_my_own_items_are_not_displayed_in_list()
    {
        Item::query()->delete();

        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $condition = Condition::first();

        $myItem = Item::factory()->create([
            'name' => '自分の出品した商品',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $otherUser = User::factory()->create();
        $otherItem = Item::factory()->create([
            'name' => '他人の出品した商品',
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }
}
