<?php

namespace App\Http\Responses;

use Laravel\Fortify\Http\Responses\LogoutResponse;

class CustomLogoutResponse extends LogoutResponse
{
    /**
     * ログアウト後のリダイレクト先をカスタマイズ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return redirect('/login');
    }
}
