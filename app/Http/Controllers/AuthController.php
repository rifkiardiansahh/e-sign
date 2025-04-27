<?php

namespace App\Http\Controllers;

use App\Http\Classes\Collector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $collector;

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }

    public function index()
    {
        if (Auth::user() === null) {
            return view('layout.guest');
        }

        if (Auth::user()->role == 1) {
            return view('layout.student')->with('name', Auth::user()->fullname);
        }

        if (Auth::user()->role == 2) {
            return view('layout.lecturer')->with('name', Auth::user()->fullname);
        }
    }

    public function loginPage()
    {
        return response(view('auth.login'), 200);
    }

    public function registerPage()
    {
        return response(view('auth.register'), 200);
    }

    public function authenticate(Request $request)
    {
        return $this->collector->user()->authenticate($request);
    }

    public function register(Request $request)
    {
        return $this->collector->user()->register($request);
    }

    public function logout(Request $request)
    {
        return $this->collector->user()->logout($request);
    }

    public function getVerificationQrcode($public_key)
    {
        return $this->collector->user()->getVerificationQrcode($public_key);
    }

    public function getImg(Request $request)
    {
        return $this->collector->user()->getImg($request);
    }
}
