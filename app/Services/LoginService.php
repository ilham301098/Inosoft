<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;


class LoginService
{
    private $validatorService;
    private $model;

    public function __construct()
    {
        $this->validatorService = new ValidatorService();
        $this->model = new User();
    }

    public function login($request)
    {
        // check if email is admin
        $select=[
            'users.id',
            'users.first_name',
            'users.status',
            'r.id as id_role',
            'r.name',
            'r.description',
        ];
        $user = User::whereHas('roleUser.role', function ($q) {
            $q->where('name','!=' ,'member');
        })
        ->join('role as r','users.role','r.name')
        ->select($select)
        ->where('status', 'aktif')
        ->first();

        $response = [];
        if (!$user) {
            $response = ResponseService::toArray(false, 'Email atau Password tidak sesuai');
            return $response;
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (!Auth::attempt($credentials)) {
            $response = ResponseService::toArray(false, 'Email atau Password tidak sesuai');
            return $response;
        }


        $tokenResult = $user->createToken('relay-auth')->plainTextToken;
        $data = [
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ];
        $response = ResponseService::toArray(true, 'Ok', $data);
        return $response;
    }

    public function logoutCurrent($request)
    {
        $user = $request->user();
        $itemUser = $this->model->where('id', $user->id)->first();
        if ($itemUser) {
            Auth::logout();
            $itemUser->tokens()->delete();
            $request->session()->forget('token');
            $request->session()->forget('user');
            $response = ResponseService::toArray(true, 'Logout successfully');
            return $response;
        }
        $response = ResponseService::toArray(false, 'Logout failed');
        return $response;
    }

}

?>
