<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;

class AuthService
{
    private $validatorService;
    private $modelUser;

    public function __construct()
    {
        $this->validatorService = new ValidatorService();
        $this->modelUser = new User();
    }

    public function login($request)
    {
        try {
            $rules = $this->modelUser->rulesAuth;
            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $response = ResponseService::toArray(false, 'Periksa kembali email yang diinput');
                return ResponseService::toJson($response);
            }

            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'status', 'aktif');

            $tokenResult = $user->createToken('relay-auth')->plainTextToken;
            
            $response = ResponseService::toArray(true, 'Login berhasil', $data);
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(true, $e->getMessage());
            return ResponseService::toJson($response, 400);
        }
    }

    public function logoutCurrent($request)
    {
        $itemUser = $request->user();
        $itemUser->currentAccessToken()->delete();
        $response = ResponseService::toArray(true, 'Ok');
        return ResponseService::toJson($response);
    }


}
