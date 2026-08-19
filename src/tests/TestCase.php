<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Stripe\Checkout\Session as StripeSession;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $mockSession = json_decode(json_encode(['url' => 'https://checkout.stripe.com/pay/dummy_session_id']));

        $this->mock('alias:' . StripeSession::class, function ($mock) use ($mockSession) {

            $mock->shouldReceive('create')->zeroOrMoreTimes()->andReturn($mockSession);
        });
    }
}
