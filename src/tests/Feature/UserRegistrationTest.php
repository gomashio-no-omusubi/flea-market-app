<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * 会員登録機能
     * ========================================================================= */

    // 名前が入力されていない場合、バリデーションメッセージが表示される
    public function test_name_is_required()
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function test_email_is_required()
    {
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function test_password_is_required()
    {
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'abcde12',
            'password_confirmation' => 'abcde12',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function test_password_must_match_confirmation()
    {
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    // 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
    public function test_successful_registration_redirects_to_profile()
    {
        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('mypage.profile.edit'));

        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);
    }
}
