<?php
/**
 * 
 * 
 */
return [
    //system
    "SUCCESS" => 10000,//成功
    "ERROR" => 10001,//失败

    //common
    "HTTP_REQUEST_NO_EXISTS" => 600000100,//请求不存在
    "HTTP_NO_ALLOWED_METHOD" => 600000101,//请求方法不允许
    "THROTTLE_ERROR" => 600000102,//请求过于频繁,
    "LIMIT_ERROR" => 600000103,//接口调用次数超过限制

    //auth
    "LICENSE_NO" => 600100100,//没有license
    "LICENSE_INVALID" => 600100101,//license invalid
    "LICENSE_EXPIRE_IN" => 600100102,//license expired
    "TOKEN_NO" => 600100103,//token必须
    "TOKEN_INVALID" => 600100104,//token无效
    "TOKEN_EXPIRES" => 600100105,//token过期
    "AUTH_NO" => 600100106,//未认证,包含token无效或过期

    "REFRESH_TOKEN_REQUIRED" => 600100107,//refresh_token必须
    "REFRESH_TOKEN_INVALID" => 600100108,//refresh_token无效
    "REFRESH_TOKEN_EXPIRES" => 600100109,//refresh_token过期

    //mqtt
    "MQTT_RECEIVE_ERROR" => 600101100,//mqtt监听回调错误
    "MQTT_CONNECT_ERROR" => 600101101,//mqtt连接失败
    "MQTT_PUBLISH_ERROR" => 600101102,//发布消息失败

    //mysql
    "MYSQL_EXEC_ERROR" => 600102100,//mysql语句执行错误

    //file
    "FILE_PUT_ERROR" => 600103100,//写文件失败

    //redis
    "REDIS_CONNECT_ERROR" => 600104100,//redis连接异常

    //validator
    "PARAMS_INVALID" => 600400100,//参数无效
    "COMPANY_REQUIRED" => 600400101,//公司名称必须
    "COMPANY_DOMAIN_REQUIRED" => 600400102,//公司服务器地址必须
    "LICENSE_EXPIRE_IN_REQUIRED" => 600400103,//license过期时间必须
    "LICENSE_EXPIRE_IN_NUMERIC" => 600400104,//license过期时间必须为数字
    "CAPTCHA_REQUIRED" => 600400105,//验证码必须
    "CAPTCHA_INVALID" => 600400106,//验证码无效
    "USER_PASSWORD_ERROR" => 600400107,//用户名或密码错误
    "USER_STATUS_NO_ALLOWED" => 600400108,//用户状态不允许
    "USER_LEVEL_NO_ALLOWED" => 600400109,//用户级别不允许
    "USER_EXISTS" => 600400110,//账号已存在
    "USER_NICKNAME_REGEX" => 600400111,//用户昵称不合法
    "USER_USERNAME_REQUIRED" => 600400112,//账号必须
    "USER_USERNAME_ALPHA_DASH" => 600400113,//账号必须是字母、数字、下划线、破折号
    "USER_USERNAME_BETWEEN" => 600400114,//账号必须为3-20个
    "USER_PASSWORD_REQUIRED" => 600400115,//,密码必须
    "USER_PASSWORD_ALPHA_DASH" => 600400116,//密码必须是字母、数字、下划线、破折号
    "USER_PASSWORD_BETWEEN" => 600400117,//密码必须为6-20个
    "USER_PASSWORD_CONFIRMED" => 600400118,//确认密码必须
    
    "EMAIL" => 600400119,//邮箱不合法
    "ADDRESS_MAX" => 600400120,//详细地址过长
    "AREA_EXISTS" => 600400121,//不支持的区域码
    "COUNTRY_PHONECODE_EXISTS" => 600400122,//不支持的国家区域码
    "PHONE" => 600400123,//手机号格式不正确
    "MAC_REGEX" => 600400124,//MAC格式错误
    "MAC_REQUIRED" => 600400125,//设备MAC必须
    "DEV_TYPE_REQUIRED" => 600400126,//设备类型必须
    "EMAIL_REQUIRED" => 600400127,//邮箱必须
    "ADDRESS_REQUIRED" => 600400128,//详细地址必须
    "USER_NO_EXISTS" => 600400129,//用户不存在
    "USER_EMAIL_ERROR" => 600400130,//用户邮箱错误
    "URL_INVALID" => 600400131,//链接无效
    "URL_EXPIRE" => 600400132,//链接过期
    "COUNTRY_PHONECODE_REQUIRED" => 600400133,//国家区域码必须
    "PHONE_REQUIRED" => 600400134,//手机号必须
    "PHONE_PHONECODE_REQUIRED" => 600400135,//手机号存在时必须有国家区域码
    "USER_PASSWORD_CONFIRMED" => 600400136,//两次密码不一致
    "USER_PASSWORD_DIFFERENT" => 600400137,//新密码不能与旧密码一样
    "USER_PASSWORD_ERROR" => 600400138,//密码错误
    "FILE_FILE" => 600400139,//文件上传不成功
    "FILE_FILEEXT" => 600400140,//不支持的文件
    "FTP_UPLOAD_FAILURE" => 6004001141,//FTP上传文件失败
    "USER_NO_WORKGROUP" => 600400142,//没有此工作组
    "NO_PERMISSTION" => 600400143,//没有权限
    "MAX_DEPTH" => 600400144,//已经到最大层级
    "GROUP_NAME_REQUIRED" => 600400145,//工作组名称必须
    "GROUP_NAME_INVALID" => 600400146,//工作组名称不合法
    "GROUP_DESC_MAX" => 600400147,//工作组描述长度最多为100
    "AUTO_WITH_CONFIG" => 600400148,//auto字段存在必须有config_id字段
    "AUTO_IN" => 600400149,//auto取值只能为0、1
    "PAGEINDEX_NUMERIC" => 600400150,//pageIndex必须是数字
    "PAGEOFFSET_NUMERIC" => 600400151,//pageOffset必须是数字
    "GID_REQUIRED" => 600400152,//工作组ID必须
    "TREE_IN" => 600400153,//tree取值只能为0,1
    "WORKGROUP_ROOT_NO" => 600400154,//根工作组不能修改或删除
    "WORKGROUP_BELONGS_CHILD" => 600400155,//有子账号拥有此工作组
    "WORKGROUP_HAS_CHILD" => 600400156,//工作组有子工作组
    "WORKGROUP_HAS_DEVICE" => 600400157,//工作组中不能有设备
    "USER_ROLE_REQUIRED" => 600400158,//用户角色必须
    "USER_ROLE_IN" => 600400159,//用户角色值必须为1或2
    "WORKGROUP_GIDS_ARRAY" => 600400160,//工作组IDs必须为数组
    "WORKGROUP_GIDS_DISTINCT" => 600400161,//工作组IDs不能重复
    "WORKGROUP_NODE_NO_TREE" => 600400162,//工作组节点不是一个完整的树结构
    "ENABLE_REQUIRED" => 600400163,//enable必须
    "ENABLE_IN" => 600400164,//enable必须为0或1
    "UNSUPPORT_SORTKEY" => 600400165,//不支持的排序key
    "UNSUPPORT_SORT" => 600400166,//不支持的排序方法
    "USER_UID_REQURIED" => 600400167,//用户ID必须
    "USER_UID_ARRAY" => 600400168,//用户ID必须为数组
    "STATUS_IN" => 600400169,//状态必须为0,1,2,3,4
    "DATE_FORMAT" => 600400170,//日期格式不正确
    "COMMID_ARRAY" => 600400171,//命令ID必须是数组
    "COMMID_DISTINCT" => 600400172,//命令ID不能重复
    "COMM_NO" => 600400173,//命令不存在
];
