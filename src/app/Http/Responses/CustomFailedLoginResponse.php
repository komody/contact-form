<?php

namespace App\Http\Responses;

use Laravel\Fortify\Http\Responses\FailedLoginResponse;

class CustomFailedLoginResponse extends FailedLoginResponse
{
    /**
     * ログイン失敗時のレスポンスをカスタマイズ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        // 認証失敗の場合はパスワードフィールドにエラーを表示
        return redirect()->route('login')
            ->withInput($request->only('email'))
            ->withErrors([
                'password' => 'ログイン情報が登録されていません',
            ], 'default');
    }
}
