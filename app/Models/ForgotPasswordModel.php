<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;                              

//jwt-auth
use Tymon\JWTAuth\Contracts\JWTSubject;

class ForgotPasswordModel extends Model implements JWTSubject
{
    protected $table = 'forgotpassword';

    protected $fillable = [
        'user_id',
    ];

    
    protected $hidden = [
        'token', 'active_status',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
