# captcha
captcha library for php

```
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Nova\Captcha\Captcha;

$captcha = new Captcha(
            [
                'fontSize' => 18,
                'length' => 4,
                'useNoise' => false,
                'codeSet' => '0123456789',
                'imageW' => 130,
                'imageH' => 50,
                'fontttf' => '5.ttf'
            ]
        );
$code = $captcha->entry();
file_put_contents('11.txt',json_encode($code));
$captcha->getImage();

//验证
/*$code = json_decode(file_get_contents('11.txt'),true);

$captcha = new Captcha();
$rs = $captcha->check($code,'0606');
var_dump($rs);*/
```
