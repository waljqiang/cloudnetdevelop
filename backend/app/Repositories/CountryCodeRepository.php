<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Entities\CountryCode;

class CountryCodeRepository extends BaseRepository{
	public function model(){
		return CountryCode::class;
	}

}