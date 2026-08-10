<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ConditionSeeder;

class ItemSearchTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 商品検索機能
     * ========================================================================= */

    // 「商品名」で部分一致検索ができる
    public function test_user_can_search_items_by_partial_match_name()
    {
        $this->seed(ConditionSeeder::class);
        $condition = Condition::first();

        $matchedItem1 = Item::factory()->create([
            'name' => '腕時計メンズ高級',
            'condition_id' => $condition->id,
        ]);
        $matchedItem2 = Item::factory()->create([
            'name' => '高級メンズ腕時計',
            'condition_id' => $condition->id,
        ]);
        $unmatchedItem = Item::factory()->create([
            'name' => 'レディースバッグ',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get(route('items.index', ['keyword' => '腕時計']));

        $response->assertStatus(200);
        $response->assertSee($matchedItem1->name);
        $response->assertSee($matchedItem2->name);
        $response->assertDontSee($unmatchedItem->name);
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_keyword_is_retained_in_mylist_tab()
    {
        $this->seed(ConditionSeeder::class);
        $condition = Condition::first();

        $user = User::factory()->create();
        $this->actingAs($user);

        $favItem = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => 'マイリスト内の腕時計',
        ]);
        $user->favoriteItems()->attach($favItem->id);

        $response1 = $this->get(route('items.index', ['keyword' => '腕時計']));
        $response1->assertStatus(200);

        $response2 = $this->get(route('items.index', ['tab' => 'mylist', 'keyword' => '腕時計']));
        $response2->assertStatus(200);

        $response2->assertSee('腕時計');
        $response2->assertSee($favItem->name);
    }
}
