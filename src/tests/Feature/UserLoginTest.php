<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class UserLoginTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * ログイン機能
     * ========================================================================= */

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required()
    {
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required()
    {
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    public function test_invalid_credentials_display_error()
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    // 正しい情報が入力された場合、ログイン処理が実行される
    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
