<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\User as UserBase;
use Laravel\Passport\HasApiTokens;
use Modules\Home\Entities\Acl;
use Modules\Home\Entities\Product;

class User extends UserBase implements JWTSubject{
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','password', 'mq_password','salt','email','phonecode','phone','appid','secret','name','idcard','enterprise','enterprise_des','enterprisecode','remember_token','is_supper','created_at','updated_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','mq_password','remember_token'
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

    public function acls(){
        return $this->hasMany(Acl::class,'username','username');
    }

    public function products(){
        return $this->hasMany(Product::class,'uid','id');
    }

}
