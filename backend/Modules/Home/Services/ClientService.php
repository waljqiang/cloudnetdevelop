<?php
namespace Modules\Home\Services;

use App\Services\BaseService;
use Carbon\Carbon;

class ClientService extends BaseService{

	public function __construct(){

	}

	public function getClient($params){
		$prtID = array_get($params,"prtid");
		dd($prtID);
	}

}