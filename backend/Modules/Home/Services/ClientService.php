<?php
namespace Modules\Home\Services;

use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Home\Repositories\ProductRepository;
use Modules\Home\Repositories\ClientRepository;
use Modules\Home\Repositories\AclRepository;

class ClientService extends BaseService{
	private $productRepository;
	private $clientRepository;
	private $aclRepository;

	public function __construct(ProductRepository $productRepository,ClientRepository $clientRepository,AclRepository $aclRepository){
		$this->productRepository = $productRepository;
		$this->clientRepository = $clientRepository;
		$this->aclRepository = $aclRepository;
	}

	public function getClient($params){
		$productID = array_get($params,"prtid");
		$mac = array_get($params,"mac");
		$time = Carbon::now()->timestamp;
		list($prtID,$salt) = decodePrtID($productID);

		$product = $this->productRepository->getInfos([["id",$prtID],["created_at",$salt]],["clients" => function($query)use($mac){
			$query->where("mac",$mac);
		}],['*'],true);
		if(!$product){
			throw new \Exception("Product is not exists",config("exceptions.PRODUCT_NO"));
		}
		$client = $product->clients->get(0);
		$username = $product->user->username;

		if(!$client){
			DB::beginTransaction();
			$cltID = $this->clientRepository->add([
				"uid" => $product->uid,
				"prtid" => $product->id,
				"mac" => $mac,
				"created_at" => $time,
				"updated_at" => $time
			]);
			$clientID = encodeCltID($prtID,$cltID,$mac);
			$rs = $username == config("mqtt.username") ? true : $this->aclRepository->addAll([
                [//允许用户发布设备上行主题
                    "allow" => 1,
                    "ipaddr" => NULL,
                    "username" => $username,
                    "clientid" => NULL,
                    "access" => 2,
                    "topic" => "{$productID}/{$clientID}/dev2app",
                    "created_at" => $time,
                    "updated_at" => $time
                ],//允许用户订阅设备下行行主题
                [
                    "allow" => 1,
                    "ipaddr" => NULL,
                    "username" => $username,
                    "clientid" => NULL,
                    "access" => 1,
                    "topic" => "{$productID}/{$clientID}/app2dev",
                    "created_at" => $time,
                    "updated_at" => $time
                ]
            ]);
            if($clientID && $rs){
            	DB::commit();
            }else{
            	DB::rollback();
            	throw new \Exception("Failure",config("exceptions.MYSQL_EXEC_ERROR"));
            }
		}else{
			$clientID = encodeCltID($prtID,$client->id,$mac);
		}
		return [
			"prtid" => $productID,
			"cltid" => $clientID,
			"server" => config("mqtt.address"),
			"port" => config("mqtt.port")
		];
	}

}