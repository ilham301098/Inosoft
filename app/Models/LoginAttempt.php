<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    use HasFactory;
    protected $table = 'login_attempt';
    protected $fillable = [
        'user_id',
        'status',
        'message',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function payload($itemUser, $status, $message = null) {
        return [
            'user_id' => $itemUser->id,
            'status' => $status,
            'message' => $message,
        ];
    }
}
