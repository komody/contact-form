<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\FailedLoginResponse;
use Laravel\Fortify\Http\Responses\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ログイン失敗時のエラーメッセージをカスタマイズ
        $this->app->singleton(FailedLoginResponse::class, function ($app) {
            return new class extends FailedLoginResponse {
                public function toResponse($request)
                {
                    // 認証失敗の場合はパスワードフィールドにエラーを表示
                    // デフォルトのauth.failedエラーを削除して、passwordフィールドにエラーを設定
                    return redirect()->route('login')
                        ->withInput($request->only('email'))
                        ->withErrors([
                            'password' => 'ログイン情報が登録されていません',
                        ]);
                }
            };
        });

        // ログアウト後のリダイレクト先をカスタマイズ
        $this->app->singleton(LogoutResponse::class, function ($app) {
            return new class extends LogoutResponse {
                public function toResponse($request)
                {
                    return redirect('/login');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
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

            // 認証失敗の場合はnullを返す（FailedLoginResponseが呼ばれる）
            return null;
        });
    }
}
