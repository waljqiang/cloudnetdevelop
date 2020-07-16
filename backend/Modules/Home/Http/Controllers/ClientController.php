<?php
namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Home\Http\Requests\ClientRequest;
use Modules\Home\Services\ClientService;

class ClientController extends Controller{
	private $clientService;

	public function __construct(ClientService $clientService){
		$this->clientService = $clientService;
	}

	public function getClient(ClientRequest $request){
		return $this->clientService->getClient($request->all());
	}

}