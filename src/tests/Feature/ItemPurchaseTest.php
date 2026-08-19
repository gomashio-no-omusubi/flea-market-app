<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class ItemPurchaseTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 商品購入機能
     * ========================================================================= */

    // 「購入する」ボタンを押下すると購入が完了する
    public function test_user_can_complete_item_purchase()
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

        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $purchasePageUrl = route('purchase.show', ['item_id' => $item->id]);

        $response = $this->from($purchasePageUrl)->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード支払い',
            'address' => 'confirmed',
            'submit_action' => 'buy',
        ]);

        $response->assertRedirectContains('checkout.stripe.com');

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_method' => 'カード支払い',
        ]);
    }

    // 購入した商品は商品一覧画面にて「Sold」と表示される
    public function test_purchased_item_is_displayed_as_sold_on_index_page()
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

        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    // 「プロフィール/購入した商品一覧」に追加されている
    public function test_purchased_item_is_added_to_profile_purchased_list()
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

        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    /* =========================================================================
     * 支払い方法選択機能
     * ========================================================================= */

    // 小計画面で変更が反映される
    public function test_selected_payment_method_is_reflected_in_purchase_page()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id
        ]);

        $purchasePageUrl = route('purchase.show', ['item_id' => $item->id]);

        $response = $this->from($purchasePageUrl)->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ払い',
            'submit_action' => 'select_payment',
        ]);

        $response->assertRedirect($purchasePageUrl);

        $redirectResponse = $this->followRedirects($response);
        $redirectResponse->assertStatus(200);
        $redirectResponse->assertSee('コンビニ払い');
    }

    /* =========================================================================
     * 配送先変更機能
     * ========================================================================= */

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_changed_address_is_reflected_in_purchase_page()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        Profile::factory()->create(['user_id' => $user->id]);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id
        ]);

        $addressData = [
            'postcode' => '123-4567',
            'address'  => '北海道室蘭市',
            'building' => 'コーポテスト101',
        ];

        $response = $this->post(route('purchase.address.store', ['item_id' => $item->id]), $addressData);

        $redirectResponse = $this->followRedirects($response);
        $redirectResponse->assertStatus(200);

        $redirectResponse->assertSee($addressData['postcode']);
        $redirectResponse->assertSee($addressData['address']);
        $redirectResponse->assertSee($addressData['building']);
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_purchased_item_is_associated_with_changed_address()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        Profile::factory()->create(['user_id' => $user->id]);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
        ]);

        $addressData = [
            'postcode' => '987-6543',
            'address'  => '沖縄県那覇市',
            'building' => 'コーポテスト202',
        ];

        $response = $this->post(route('purchase.address.store', ['item_id' => $item->id]), $addressData);

        $response->assertSessionHas("changed_address_{$item->id}", $addressData);

        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'submit_action' => 'buy',
            'payment_method' => 'カード支払い',
            'address' => 'confirmed',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id'  => $user->id,
            'item_id'  => $item->id,
            'payment_method'    => 'カード支払い',
            'shipping_postcode' => '987-6543',
            'shipping_address'  => '沖縄県那覇市',
            'shipping_building' => 'コーポテスト202',
        ]);
    }
}
