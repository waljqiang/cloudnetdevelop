<?php
namespace App\Entities;

use App\Models\Model;

class CountryCode extends Model{
	protected $table = "country_code";
	protected $primaryKey = "id";

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "id","created_at","updated_at"
    ];
}