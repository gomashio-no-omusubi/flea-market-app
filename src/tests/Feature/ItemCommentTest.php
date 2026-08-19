<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;


class ItemCommentTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * コメント送信機能
     * ========================================================================= */

    // ログイン済みのユーザーはコメントを送信できる
    public function test_logged_in_user_can_send_comment()
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

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => 'テストコメントです。'
        ]);

        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'テストコメントです。'
        ]);

        $redirectResponse = $this->followRedirects($response);
        $redirectResponse->assertSee('テストコメントです。');
        $redirectResponse->assertSeeInOrder([
            'コメント',
            '1'
        ]);
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_guest_user_cannot_send_comment()
    {
        $this->seed(ConditionSeeder::class);

        $seller = User::factory()->create();
        $condition = Condition::first();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id
        ]);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => 'ログイン前のコメントは送信できない'
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'content' => 'ログイン前のコメントは送信できない'
        ]);
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_is_required()
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

        $response = $this->from(route('items.show', ['item_id' => $item->id]))->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => ''
        ]);

        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));

        $response->assertSessionHasErrors(['content' => '商品コメントは、必ず指定してください。']);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_cannot_be_more_than_255_characters()
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

        $response = $this->from(route('items.show', ['item_id' => $item->id]))->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => str_repeat('A', 256)
        ]);

        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));

        $response->assertSessionHasErrors(['content' => '商品コメントは、255文字以下にしてください。']);
    }
}
