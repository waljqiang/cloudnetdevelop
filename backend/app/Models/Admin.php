<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\User as UserBase;

class Admin extends UserBase implements JWTSubject{
    protected $table = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password', 'email','phone','wechat_appid','wechat_secret','remember_token','is_del','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token','is_del'
    ];

    protected $dateFormat = 'U';

    /** @var attribute trans */
    protected $casts = [
        
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }

    public function getEmailAttribute($value){
        return !empty($value) ? $value : '';
    }

    public function getPhoneAttribute($value){
        return !empty($value) ? $value : '';
    }

    public function getWechatAppidAttribute($value){
        return !empty($value) ? $value : '';
    }

    public function getWechatSecretAttribute($value){
        return !empty($this->wechat_appid) && !empty($value) ? $value : '';
    }

}
