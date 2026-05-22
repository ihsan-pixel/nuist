<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function login()
    {
        return redirect()->route('login');
    }

    public function authenticate(Request $request, LoginController $loginController)
    {
        return $loginController->login($request);
    }

    public function dashboard()
    {
        return redirect()->route('dashboard');
    }
}
