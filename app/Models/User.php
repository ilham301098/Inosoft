<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, UsesUuid;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'profile_picture',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'gender',
        'address',
        'birthdate',
        'role',
        'city',
        'password',
        'last_online_at',
        'verify_token',
        'email_verified_at',
        'phone_verified_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $rulesAuth = [
        'email' => 'required',
        'password' => 'required'
    ];

    public $rulesRegister = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'password' => 'min:6|required',
        'phone' => 'required',
        'gender' => 'required',
    ];

    public $rulesAddUser = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'role' => 'required',
    ];

    public function payloadAddUser($request)
    {
        $payload = $request->all();
        $payload['password'] = Hash::make($request->password);
        $payload['role'] = $request->role;
        $payload['gender'] = strtolower($request->gender);
        $payload['verify_token'] = Str::random(32);
        $payload['language'] = 'id';
        return $payload;
    }

    public function payloadRegister($request)
    {
        $payload = $request->all();
        $payload['password'] = Hash::make($request->password);
        $payload['role'] = 'member';
        $payload['gender'] = strtolower($request->gender);
        $payload['verify_token'] = Str::random(32);
        $payload['referral_code'] = substr(strtolower($request->first_name . $request->last_name), 0, 6) . rand(100, 999);
        $payload['language'] = 'id';

        return $payload;
    }

    public $ruleUpdatePassword = [
        'password_lama' => 'required',
        'password_baru' => 'required|min:6',
        'password_baru_confirmation' => 'required|min:6|same:password_baru',
    ];

    public function payloadUpdatePassword($request)
    {
        $newPassword = Hash::make($request->password_baru);
        $payload['password'] = $newPassword;
        return $payload;
    }

    public $rulesUpdateProfile = [
        'first_name' => 'required',
        'phone' => 'required',
    ];

    public function payloadUpdateProfile($request)
    {
        $payload = $request->all();
        $payload['birthdate'] = date('Y-m-d', strtotime($request->birthdate));
        return $payload;
    }

    public function payloadUpdateProfileDashboard($request)
    {
        return [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ];
    }

    public function payloadUpdatePasswordDashboard($request)
    {
        return [
            'password' => Hash::make($request->password)
        ];
    }

    public function roleUser(): HasOne
    {
        return $this->hasOne(RoleUser::class, 'user_id');
    }

}
