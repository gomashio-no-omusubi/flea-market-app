<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;

class UserProfileTest extends TestCase
{

    use DatabaseTransactions;

    /* =========================================================================
     * ユーザー情報取得
     * ========================================================================= */

    // 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_user_can_view_own_profile_with_required_information()
    {
        $this->seed(ConditionSeeder::class);

        $user = User::factory()->create(['name' => 'テストユーザー']);
        $this->actingAs($user);

        Profile::create([
            'user_id' => $user->id,
            'postcode' => '000-0000',
            'address' => '初期住所',
            'img_url' => 'profile/test_avatar.jpg',
        ]);

        $condition = Condition::first();

        $boughtItem = Item::factory()->create([
            'name' => '購入した商品A',
            'condition_id' => $condition->id,
        ]);

        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->item_id = $boughtItem->id;
        $purchase->shipping_postcode = '000-0000';
        $purchase->shipping_address = 'お届け先住所';
        $purchase->payment_method = 'stripe';
        $purchase->save();

        Item::factory()->create([
            'name' => '出品した商品B',
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $responseBuy = $this->get(route('mypage.index', ['page' => 'buy']));
        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('テストユーザー');
        $responseBuy->assertSee('購入した商品A');
        $responseBuy->assertSee('profile/test_avatar.jpg');

        $responseSell = $this->get(route('mypage.index', ['page' => 'sell']));
        $responseSell->assertStatus(200);
        $responseSell->assertSee('出品した商品B');
    }

    /* =========================================================================
     * ユーザー情報変更
     * ========================================================================= */

    // 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_profile_edit_page_shows_previously_set_initial_values()
    {
        $user = User::factory()->create(['name' => '過去設定されたユーザー']);
        $this->actingAs($user);

        Profile::create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '過去設定された住所',
            'img_url' => 'profile/past_avatar.jpg',
        ]);

        $response = $this->get(route('mypage.profile.edit'));
        $response->assertStatus(200);

        $response->assertSee('過去設定されたユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('過去設定された住所');
        $response->assertSee('profile/past_avatar.jpg');
    }
}
