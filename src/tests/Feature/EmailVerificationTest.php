<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
    use DatabaseTransactions;

    /* =========================================================================
     * メール認証機能
     * ========================================================================= */

    //会員登録後、認証メールが送信される
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    //メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_email_can_be_verified_and_redirects_to_profile()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee('認証はこちら');
    }

    //メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function test_completing_mail_verification_redirects_to_profile_setting()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification())
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('mypage.profile.edit'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
