<?php
use Carbon\Carbon;

function getStr($len = 6,$source = '0123456789'){
    $str = '';
    for($i = 0; $i < $len; $i++){
    	$str .= iconv_substr($source,floor(mt_rand(0,mb_strlen($source,'utf-8')-1)),1,'utf-8');
    }
    return $str;
}

//根据时间戳、时区、夏令时将时间转换为相应的时区时间gmdate
function convUnixToZoneGm($timestamp,$timeZone,$summerTime){
	return Carbon::createFromFormat("Y-m-d H:i:s",date('Y-m-d H:i:s',$timestamp))->addMinutes($timeZone * 60)->addMinutes
	($summerTime * 60)->toDateTimeString();
}

//根据时间、时区、夏令时将时间转换为相应的时区时间gmdate
function convDateToZoneGm($date,$timeZone,$summerTime){
	return Carbon::createFromFormat("Y-m-d H:i:s",$date)->addMinutes($timeZone * 60)->addMinutes($summerTime * 60)->toDateTimeString();
}

//获取客户端真是ip
function getClientIp(){
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    return $res;
}

//mac地址添加冒号
function setMac($mac){
    return substr($mac, 0, 2) . ':' . substr($mac, 2, 2) . ':' . substr($mac, 4, 2) . ':' . substr($mac, 6, 2) . ':' . substr($mac, 8, 2) . ':' . substr($mac, 10, 2);
}

//mac地址去除冒号
function parseMac($mac){
    return str_replace(':', '', $mac);
}

/**
 * tree
 *
 * @param  array $items 数据
 * @param  string $id    数据标识key
 * @param  string $pid   数据父ID标识key
 * @param  string $sub   子节点名称
 * @param  string $order 排序字段
 * @param  [type] $sort  排序方法
 * @param  array  $root  根节点，如果有值，则所有节点全部放在根节点下
 * @param  boolean $modifyPid 如果节点中pid与跟节点id不一致时，是否修改节点pid为根节点id
 * @return array
 */
function rankSort($items,$id='id',$pid='pid',$sub='sub',$order='id',$sort=SORT_ASC,$root=[],$modifyPid=false){
    
    $items = array_combine(array_column($items,$id),$items);

    $tree = [];
    if(!empty($items)){
        foreach($items as $item){
            if(isset($items[$item[$pid]])){
                if(!isset($items[$item[$id]][$sub]))
                    $items[$item[$id]][$sub] = [];
                $items[$item[$pid]][$sub][] = &$items[$item[$id]];
            }else{
                $tree[] = &$items[$item[$id]];
            }
        }
    }

    $res = $tree;
    unset($items);
    unset($tree);

    if(!empty($root)){
        if(!empty($res)){
            foreach ($res as $node) {
                if($modifyPid){
                    $node[$pid] = $root[$id];
                }
                $root[$sub][] = $node;
            }
        }
        $res = [$root];
    }

    array_multisort(array_column($res,$order),$sort,$res);
    return $res;
}

function getCommandID($lotType,$messageType,$time = ""){
    return !empty($time) ? str_pad(dechex($lotType),4,"0",STR_PAD_LEFT) . str_pad(dechex($messageType),4,"0",STR_PAD_LEFT) . $time : str_pad(dechex($lotType),4,"0",STR_PAD_LEFT) . str_pad(dechex($messageType),4,"0",STR_PAD_LEFT) . time();
}