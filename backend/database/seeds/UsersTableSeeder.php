<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $time = Carbon::now()->timestamp;
    	$res = DB::connection()->table("users")->first();
    	if(empty($res)){
	        DB::connection()->table("users")->insertGetId([
                "id" => 1,
        		"username" => config("mqtt.username"),
        		"password" => bcrypt(config("mqtt.password")),
                "mq_password" => config("mqtt.password"),
        		"email" => "11@11.com",
        		"phonecode" => "86",
        		"phone" => "13468826445",
        		"appid" => md5("1" . $time),
        		"secret" => str_random(40),
        		"name" => "系统测试号",
        		"idcard" => "631456199007157245",
        		"enterprise" => "XXX企业",
        		"enterprise_des" => "XXX企业致力于网络设备研发，提供公共场所网络解决方案。",
                "enterprisecode" => "XXXXXXXXXXXXXXXXXX",
        		"created_at" => $time,
        		"updated_at" => $time
        	]);
	    }
    }
}
