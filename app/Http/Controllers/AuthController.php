<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $authService;
    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logoutCurrent($request);
    }

    public function loginSMS(Request $request)
    {
        return $this->authService->loginSMS($request);
    }
}
