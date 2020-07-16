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

function encodePrtID($prtID,$salt){
    $prtIDLength = str_pad(strlen($prtID),2,0,STR_PAD_LEFT);
    return app("Hashprtids")->encodeHash($prtID . $salt . $prtIDLength);
}

function decodePrtID($encrypt){
    $prtIDArr = app("Hashprtids")->decodeHash($encrypt);
    if(count($prtIDArr) != 1){
        throw new \Exception("The prtid is error",config("exceptions.PRTID_ERROR"));
    }
    $prtIDLength = intval(substr($prtIDArr[0],-2));
    $str = substr($prtIDArr[0],0,-2);
    return [substr($str,0,$prtIDLength),substr($str,$prtIDLength)];
}

function encodeCltID($prtID,$cltID,$mac){
    $macdec = hexdec(parseMac($mac));
    $prtLength = str_pad(strlen($prtID),2,0,STR_PAD_LEFT);
    $cltLength = str_pad(strlen($cltID),2,0,STR_PAD_LEFT);
    return app("Hashcltids")->encodeHash($prtID . $cltID . $macdec . $prtLength . $cltLength);
}

function decodeCltID($encrypt){
    $cltIDArr = app("Hashcltids")->decodeHash($encrypt);
    if(count($cltIDArr) != 1){
        throw new \Exception("The cltid is error",config("exceptions.CLT_ERROR"));
    }

    $cltLength = intval(substr($cltIDArr[0],-2));
    $prtLength = intval(substr($cltIDArr[0],-4,-2));
    $str = substr($cltIDArr[0],0,-4);
    return [
        substr($str,0,$prtLength),//产品ID
        substr($str,$prtLength,$cltLength),//客户端ID
        strtoupper(setMac(dechex(substr($str,($prtLength+$cltLength)))))//mac地址
    ];
}

//mac地址添加冒号
function setMac($mac){
    return substr($mac, 0, 2) . ':' . substr($mac, 2, 2) . ':' . substr($mac, 4, 2) . ':' . substr($mac, 6, 2) . ':' . substr($mac, 8, 2) . ':' . substr($mac, 10, 2);
}

//mac地址去除冒号
function parseMac($mac){
    return str_replace(':', '', $mac);
}