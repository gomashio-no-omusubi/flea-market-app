<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;

class ItemFavoriteTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * いいね機能
     * ========================================================================= */

    // いいねアイコンを押下することによって、いいねした商品として登録することができる。追加済みのアイコンは色が変化する
    public function test_user_can_favorite_item_and_icon_changes_with_count()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id
        ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->post(route('favorites.toggle', ['item_id' => $item->id]));

        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this->get(route('items.show', ['item_id' => $item->id]));
        $detailResponse->assertStatus(200);

        $detailResponse->assertSeeInOrder([
            'いいね済',
            '1'
        ]);
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    public function test_user_can_unfavorite_item_and_icon_reverts_with_count()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id
        ]);

        $user->favoriteItems()->attach($item->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->post(route('favorites.toggle', ['item_id' => $item->id]));

        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $detailResponse = $this->get(route('items.show', ['item_id' => $item->id]));
        $detailResponse->assertStatus(200);

        $detailResponse->assertSeeInOrder([
            '未いいね',
            '0'
        ]);
    }
}
