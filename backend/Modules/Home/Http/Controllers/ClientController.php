<?php
namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Home\Services\ClientService;

class ClientController extends Controller{
	private $clientService;

	public function __construct(ClientService $clientService){
		$this->clientService = $clientService;
	}

	public function getClient(Request $request){
		return $this->clientService->getClient($request->all());
	}

}