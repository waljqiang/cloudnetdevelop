<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Home\Repositories\UserRepository;
use Modules\Home\Repositories\AclRepository;

class RegisterProduct extends Command
{
    private $userRepository;
    private $aclRepository;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:register {username} {appid} {appsecret} {productname} {type} {size} {productdes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The develop user register product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository,AclRepository $aclRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->aclRepository = $aclRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');
        $appid = $this->argument('appid');
        $appsecret = $this->argument('appsecret');
        $productname = $this->argument('productname');
        $productDes = $this->argument('productdes');
        $type = $this->argument('type');
        $size = $this->argument('size');
        $time = Carbon::now()->timestamp;

        if(!in_array($type,config('device.type'))){
            $this->error('Unsupport the type of the device');
            exit(-1);
        }

        $user = $this->userRepository->getInfos([["username",$username]],["products" => function($query)use($type,$size){
            $query->where("product.type",$type)->where("product.size",$size);
        }],['*'],true);
        
        if(empty($user)){
            $this->error('The user is not exists');
            exit(-1);
        }

        if($appid != $user->appid || $appsecret != $user->secret){
            $this->error('The appid or appsecret is not incorrect');
            exit(-1);
        }

        $hashprtids = app("Hashprtids");
        
        $products = $user->products;
        if(!$products->isEmpty()){
            $this->error('The product is exists');
            $product = $products->get(0);
            $productID = $hashprtids->encodeHash($product->id . $product->created_at);
            $this->line("<comment>product Id:{$productID}</comment>");
            exit(-1);
        }else{
            //DB::beginTransaction();
            $product = $user->products()->create([
                "name" => $productname,
                "describe" => $productDes,
                "type" => $type,
                "size" => $size,
                "status" => 1,
                "created_at" => $time,
                "updated_at" => $time
            ]);
            $productID = $hashprtids->encodeHash($product->id . $product->created_at);
            /*$rs = $user->username == config("mqtt.username") ? true : $this->aclRepository->addAll([
                [//允许用户发布设备上行主题
                    "allow" => 1,
                    "ipaddr" => NULL,
                    "username" => $user->username,
                    "clientid" => NULL,
                    "access" => 2,
                    "topic" => "{$productID}/%c/dev2app",
                    "created_at" => $time,
                    "updated_at" => $time
                ],//允许用户订阅设备下行行主题
                [
                    "allow" => 1,
                    "ipaddr" => NULL,
                    "username" => $user->username,
                    "clientid" => NULL,
                    "access" => 1,
                    "topic" => "{$productID}/%c/app2dev",
                    "created_at" => $time,
                    "updated_at" => $time
                ]
            ]);
            if($product && $rs){
                DB::commit();*/
                $this->info('New client created successfully.');
                $this->line("<comment>product Id:{$productID}</comment>");
            /*}else{
                DB::rollback();
                $this->error("Failure");
            }*/
        }
    }
}
