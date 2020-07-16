<?php
namespace Modules\Home\Entities;

use App\Models\Model;
use App\Models\User;

class Product extends Model{
	protected $table = "product";
	protected $primaryKey = "id";

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'id','uid','name','describe','type','size','status','created_at','updated_at'
    ];
	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function user(){
        return $this->belongsTo(User::class,"id","uid");
    }
}