<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ConditionSeeder;

class ItemRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 出品商品情報登録
     * ========================================================================= */

    //　商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
    public function test_user_can_register_item_with_required_information()
    {
        $this->seed(CategorySeeder::class);
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $category  = Category::first();
        $condition = Condition::first();

        $requestData = [
            'categories' => [$category->id],
            'condition_id' => $condition->id,
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'description' => 'This is a description of the test item.',
            'price' => 15000,
        ];

        $response = $this->post(route('items.store'), $requestData);

        $response->assertRedirect();

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'description' => 'This is a description of the test item.',
            'price' => 15000,
        ]);

        $item = Item::latest('id')->first();

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
            'item_id' => $item->id,
        ]);
    }
}
