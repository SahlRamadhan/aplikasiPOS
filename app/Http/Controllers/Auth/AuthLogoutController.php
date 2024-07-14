<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthLogoutController extends Controller
{
    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
