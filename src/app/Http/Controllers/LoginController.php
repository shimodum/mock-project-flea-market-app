<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    // ログインフォームを表示する
    public function showForm()
    {
        return view('auth.login');
    }

    // ログイン処理を行う

}
