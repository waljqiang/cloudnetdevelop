SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cnl_users
-- ----------------------------
DROP TABLE IF EXISTS `cnldev_users`;
CREATE TABLE `cnldev_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `mq_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'mq密码',
  `salt` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '加密盐值',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
  `phonecode` varchar(255) NOT NULL COMMENT '国家区域码',
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `appid` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '开发者id',
  `secret` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '开发者秘钥', 
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '姓名',
  `idcard` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '身份证',
  `enterprise` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '企业名称',
  `enterprise_des` text DEFAULT NULL COMMENT '企业描述',
  `enterprisecode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '全国统一信用码',
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_supper` tinyint(1) DEFAULT 0 COMMENT '是否是超级用户,0:否,1:是',
  `created_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户表';


-- ----------------------------
-- Table structure for cnldev_product
-- ----------------------------
DROP TABLE IF EXISTS `cnldev_product`;
CREATE TABLE `cnldev_product` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '产品名称',
  `describe` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '产品描述',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '产品类型,1:网络设备',
  `size` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '产品型号',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '产品状态表1:注册未发布,2:发布审核中,3:发布失败,4:发布成功',
  `created_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='产品表';

-- ----------------------------
-- Table structure for cnldev_client
-- ----------------------------
DROP TABLE IF EXISTS `cnldev_client`;
CREATE TABLE `cnldev_client` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `prtid` bigint(20) UNSIGNED NOT NULL COMMENT '产品ID',
  `mac` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '设备MAC地址',
  `created_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='客户端表';

-- ----------------------------
-- Table structure for cnldev_acl
-- ----------------------------
DROP TABLE IF EXISTS `cnldev_acl`;
CREATE TABLE `cnldev_acl` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `allow` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '0:deny,1:allow',
  `ipaddr` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'IpAddress',
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'username',
  `clientid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'clientid',
  `access` tinyint(2) UNSIGNED NOT NULL COMMENT '1:subscribe,2:publish,3:pubsub',
  `topic` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'topic filter',
  `created_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='MQTT acl表';

-- ----------------------------
-- Table structure for cnldev_country_code
-- ----------------------------
DROP TABLE IF EXISTS `cnldev_country_code`;
CREATE TABLE `cnldev_country_code` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name_zh` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '中文国家名',
  `name_en` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '英文国家名',
  `short2` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '两位国家码',
  `short3` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '三位国家码',
  `num` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '数字国家码',
  `phonecode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '国家电话区域码',
  `created_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint(13) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='国家码表';

SET FOREIGN_KEY_CHECKS = 1;