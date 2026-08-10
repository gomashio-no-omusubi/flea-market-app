<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Requests\RegisterRequest as FortifyRegisterRequest;
use App\Http\Requests\RegisterRequest;

use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FortifyRegisterRequest::class, RegisterRequest::class);
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        $this->app->instance(
            RegisterResponse::class,
            new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect('/mypage/profile');
                }
            }
        );

        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(
            function () {
                return view('auth.register');
            }
        );

        Fortify::loginView(
            function () {
                return view('auth.login');
            }
        );

        Fortify::verifyEmailView(
            function () {
                return view('auth.verify-email');
            }
        );

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
