<?php

namespace App\Utils;

/**
 * @author magus.lee
 */
class HashCode{

    /**
     * hash加密
     * @param $string
     * @return string
     * @author magus.lee
     */

    public static function encrypt($string,$key = 'cloudnetlot', $expiry = 0,$ckey_length = 6){
        $check_key = strrev(strtolower($key));
        $keya = md5(substr(md5($key), 0, 16));
        $keyb = md5(substr(md5($key), 16, 16));
        $keyc = $ckey_length ? substr(md5(time()), -$ckey_length) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        $string = sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        return $check_key.$keyc.bin2hex($result);
    }


    /**
     * hash加密
     * @param $string
     * @return string
     * @author magus.lee
     */
    public static function decrypt($string,$key = 'cloudnetlot',$ckey_length = 6){
        $check_key = strrev(strtolower($key));
        $string = substr($string,strlen($check_key));

        $keya = md5(substr(md5($key), 0, 16));
        $keyb = md5(substr(md5($key), 16, 16));
        $keyc = $ckey_length ? substr($string, 0, $ckey_length) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = hex2bin(substr($string, $ckey_length));
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }

    }

}