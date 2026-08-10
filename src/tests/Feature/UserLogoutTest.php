<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class UserLogoutTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * ログアウト機能
     * ========================================================================= */

    // ログアウト処理が実行される
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $this->assertGuest();

        $response->assertRedirect(route('login'));
    }
}
