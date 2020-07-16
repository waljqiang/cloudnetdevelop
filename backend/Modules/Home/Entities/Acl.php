<?php
namespace Modules\Home\Entities;

use App\Models\Model;
use App\Models\User;

class Acl extends Model{
	protected $table = "acl";
	protected $primaryKey = "id";

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'id','allow','ipaddr','username','clientid','access','topic','created_at','updated_at'
    ];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function user(){
        return $this->belongsTo(User::class,"username","username");
    }
}