<?php

return [
    "defaultpassword" => env("MQ_PASSWORD","123456"),
    "timeZone" => env("APP_TIMEZONE","+08:00"),
    "isSummerTime" => env("APP_ISSUMMERTIME","0"),
    "pageIndex" => 1,
    "pageOffset" => 10,
    "mailtopassword" => [
        "expire_in" => 600,//过期时间10分钟
        "trans" => [
            "zh" => [
                "subject" => "CloudNetLotDevelop-找回密码",
                "lang1" => "尊敬的",
                "lang2" => "您正在进行找回密码操作,请点击以下链接修改密码,如果点击此链接并未进行跳转,请尝试复制此链接到浏览器打开.请勿将此链接转发他人,该链接有效期为%s分钟.",
                "lang3" => "本邮件由CloudnetLot平台自动发出,请勿直接回复.",
            ],
            "en" => [
                "subject" => "CloudNetLotDevelop Platform-find password",
                "lang1" => "Dear ",
                "lang2" => "You are in the process of retrieving your password. Please click on the link below to modify your password. If you click on this link without jumping, please try to copy this link to the browser to open it. Do not forward this link to anyone else. The link is valid for %s minutes.",
                "lang3" => "This email is sent automatically by the CloudNetLotDevelop Platform. Please do not reply directly.",
            ],
        ]
    ],
];
