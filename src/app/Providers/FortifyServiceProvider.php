<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\CustomLogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ログアウト後のリダイレクト先をカスタマイズ
        $this->app->singleton(LogoutResponseContract::class, CustomLogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        // ログインビューをカスタマイズ
        Fortify::loginView(function () {
            return view('login');
        });

        // 登録ビューをカスタマイズ
        Fortify::registerView(function () {
            return view('register');
        });

        // ログイン時のバリデーションを追加
        Fortify::authenticateUsing(function (Request $request) {
            // バリデーション
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスはメール形式で入力してください',
                'password.required' => 'パスワードを入力してください',
            ]);

            if ($validator->fails()) {
                // バリデーションエラーをスロー
                throw \Illuminate\Validation\ValidationException::withMessages($validator->errors()->toArray());
            }

            // 認証処理
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }

            // 認証失敗時はパスワード欄にエラーを出す
            throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => 'ログイン情報が登録されていません',
            ]);
        });
    }
}
