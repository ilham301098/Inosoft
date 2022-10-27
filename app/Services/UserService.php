<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserSimpleResource;
use App\Jobs\RegisterJob;
use App\Jobs\ResetPasswordJob;
use App\Jobs\TokenResetJob;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserService
{
    private $modelUser, $modelPasswordReset, $modelRole;
    private $validatorService;
    private $authService;
    public function __construct()
    {
        $this->modelUser = new User();
        $this->modelPasswordReset = new PasswordReset();
        $this->validatorService = new ValidatorService();
        $this->authService = new AuthService();
        $this->modelRole = new Role();
    }
    /**
     * CMS SERVICE
     */
    public function addUser($request)
    {

        try {

            $rules = $this->modelUser->rulesAddUser;
            $id_role = $this->modelRole::find($request->role);

            $request->merge(["password" => '123456']);
            if ($id_role) $request->merge(["role" => $id_role->name]);

            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }
            if (str_contains($request->phone, '+62')) {
                $temp = substr($request->phone, 3);
                $output = preg_replace('/[^0-9]/', '', substr($request->phone, 3));
                if ($output == "") return ResponseService::toArray(false, 'Email sudah terdaftar', [], ['phone' => 'Phone number not valid']);
            } else {
                $output = preg_replace('/[^0-9]/', '', substr($request->phone, 3));
                if ($output == "") return ResponseService::toArray(false, 'Email sudah terdaftar', [], ['phone' => 'Phone number not valid']);
                $temp = substr($request->phone, 1);
                $patter = '+62';
                $request->merge(["phone" => $patter . $temp]);
            }

            $emailExists = $this->modelUser->where('email', $request->email)->first();
            if ($emailExists) {
                $response = ResponseService::toArray(false, 'Email sudah terdaftar', [], ['email' => 'Email already registered']);
                return ResponseService::toJson($response);
            }

            $payload = $this->modelUser->payloadAddUser($request);
            $itemUser = $this->modelUser->create($payload);
            RoleUser::create([
                'user_id' => $itemUser->id,
                'role_id' => $id_role->id
            ]);
            dispatch(new RegisterJob($itemUser));
            $response = ResponseService::toArray(true, 'Registrasi berhasil', $itemUser);
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response, 400);
        }
    }

    public function editUser($request)
    {
        try {
            $rules = $this->modelUser->rulesAddUser;

            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            if (str_contains($request->phone, '+62')) {
                $temp = substr($request->phone, 3);
                $output = preg_replace('/[^0-9]/', '', substr($request->phone, 3));

                if ($output == "") return ResponseService::toArray(false, 'Email sudah terdaftar', [], ['phone' => ['Phone number not valid']]);
            } else {
                $output = preg_replace('/[^0-9]/', '', substr($request->phone, 3));

                if ($output == "") return ResponseService::toArray(false, 'Email sudah terdaftar', [], ['phone' => ['Phone number not valid']]);
                $temp = substr($request->phone, 1);
                $patter = '+62';
                $request->merge(["phone" => $patter . $temp]);
            }

            $itemUser = $this->modelUser->find($request->id);

            RoleUser::where('id', $itemUser->roleUser->id)->update([
                'role_id' => $request->role
            ]);

            $request->request->remove('id');
            $request->request->remove('_token');
            $role = $this->modelRole->find($request->role);
            $request->merge(["role" => $role->name]);
            $payload = $this->modelUser->payloadAddUser($request);
            $itemUser->update($payload);
            dispatch(new RegisterJob($itemUser));
            $response = ResponseService::toArray(true, 'Registrasi berhasil', $itemUser);
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response, 400);
        }
    }

    /**
     * API SERVICE
     */
    public function register($request)
    {
        try {
            $rules = $this->modelUser->rulesRegister;
            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            $emailExists = $this->modelUser->where('email', $request->email)->first();
            if ($emailExists) {
                $response = ResponseService::toArray(false, 'Email sudah terdaftar');
                return ResponseService::toJson($response);
            }

            $payload = $this->modelUser->payloadRegister($request);
            $itemUser = $this->modelUser->create($payload);
            $this->authService->assignRoleUser($itemUser->id);
            dispatch(new RegisterJob($itemUser));
            $response = ResponseService::toArray(true, 'Registrasi berhasil', $itemUser);
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response, 400);
        }
    }

    public function updateProfilePicture($request)
    {
        try {
            $rules = [
                'image' => 'mimes:jpeg,png,jpg|max:10000',
            ];
            if ($this->validatorService->isValidateFail($request, $rules)) {
                return ResponseService::validatorErrorAlt($request, $rules);
            }

            $itemUser = $request->user();

            $mediaService = new MediaService();
            $debug=[
                'image'=>$request->image,
                'user_id'=>$itemUser->id
            ];
            // dd($debug);
            $itemImage = $mediaService->storeMedia($request->image, $itemUser->id);
            if ($itemImage == null) {
                $response = ResponseService::toArray(false, 'Upload failed',$debug);
                return ResponseService::toJson($response);
            }

            //Unlink old media
            $mediaService->deleteMedia($itemUser->profile_picture);

            $itemUser->update([
                'profile_picture' => $itemImage->path
            ]);
            $response = ResponseService::toArray(true, 'Profile picture updated', new UserSimpleResource($itemUser));
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            return ResponseService::catchError($e->getMessage(), 500);
        }
    }

    public function forgetPassword($request)
    {
        $rules = $this->modelPasswordReset->rulesForgetPassword;
        if ($this->validatorService->isValidateFail($request, $rules)) {
            $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
            return ResponseService::toJson($response);
        }

        $itemUser = $this->modelUser->where('email', $request->email)->first();
        if (!$itemUser) {
            $response = ResponseService::toArray(false, 'Email tidak terdaftar');
            return ResponseService::toJson($response);
        }
        $payload = $this->modelPasswordReset->payloadForgetPassword($request);
        $token = Str::random(32);
        $payload['token'] = $token;
        $this->modelPasswordReset->create($payload);

        dispatch(new TokenResetJob($itemUser, $token));
        $response = ResponseService::toArray(true, 'Token send to your email');
        return ResponseService::toJson($response);
    }

    public function resetPassword($request)
    {
        $rules = $this->modelPasswordReset->rulesResetPassword;
        if ($this->validatorService->isValidateFail($request, $rules)) {
            $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
            return ResponseService::toJson($response);
        }

        $itemToken = $this->modelPasswordReset->where('token', $request->token)
        ->where('email', $request->email)
        ->first();
        if (!$itemToken) {
            $response = ResponseService::toArray(false, 'Token Invalid');
            return ResponseService::toJson($response);
        }

        $itemUser = $this->modelUser->where('email', $request->email)->first();
        if (!$itemUser) {
            $response = ResponseService::toArray(false, 'Email tidak terdaftar');
            return ResponseService::toJson($response);
        }

        $newPassword = Hash::make($request->password);
        $itemUser->update(['password' => $newPassword]);

        $this->modelPasswordReset->where('email', $itemUser->email)->delete();

        dispatch(new ResetPasswordJob($itemUser));
        $response = ResponseService::toArray(true, 'Reset password berhasil');
        return ResponseService::toJson($response);
    }

    public function profile($request)
    {
        $itemUser = $request->user();

        if (is_null($itemUser->referral_code)) {
            $payload = $this->modelUser->generateReferral($itemUser);
            $itemUser->update($payload);
        }
        $response = ResponseService::toArray(true, 'Ok', new UserResource($itemUser));
        return ResponseService::toJson($response);
    }

    public function updateProfile($request)
    {
        try {
            $rules = $this->modelUser->rulesUpdateProfile;
            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            $payload = $this->modelUser->payloadUpdateProfile($request);
            $itemUser = $request->user();
            $itemUser->update($payload);

            $response = ResponseService::toArray(true, 'Profil berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

    public function updateLanguage($request)
    {
        try {
            $rules = [
                'language' => 'required|in:id,en',
            ];
            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            $payload = ['language'=>$request->language];
            $itemUser = $request->user();
            $itemUser->update($payload);

            $response = ResponseService::toArray(true, 'Bahasa Pengguna berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

    public function updatePassword($request)
    {
        try {
            $rules = $this->modelUser->ruleUpdatePassword;
            if ($this->validatorService->isValidateFail($request, $rules)) {
                $response = ResponseService::validatorError($this->validatorService->getErrors($request, $rules));
                return ResponseService::toJson($response);
            }

            $itemUser = $request->user();
            if (!Hash::check($request->password_lama, $itemUser->password)) {
                $response = ResponseService::toArray(false, 'Password failed');
                return ResponseService::toJson($response);
            }
            $payload = $this->modelUser->payloadUpdatePassword($request);
            $itemUser->update($payload);
            $itemUser->tokens()->delete();
            $response = ResponseService::toArray(true, 'Password berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

    public function lastOnline($limit = 10, $orderBy = 'id', $sort = 'asc')
    {
        $itemUser =  $this->modelUser->orderBy($orderBy, $sort)->paginate($limit);
        $itemUser->withPath('?limit=' . $limit . '&order-by=' . $orderBy . '&sort=' . $sort);
        return $itemUser;
    }
}
