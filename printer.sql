/*
 Navicat Premium Data Transfer

 Source Server         : 谢-华为tenancy
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : 120.46.172.212:3399
 Source Schema         : tenancy

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 15/12/2023 17:34:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for w_printer_task
-- ----------------------------
DROP TABLE IF EXISTS `w_printer_task`;
CREATE TABLE `w_printer_task` (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `order_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '0' COMMENT '打印订单号',
  `user_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '0' COMMENT '用户id',
  `print_type` tinyint(1) DEFAULT NULL COMMENT '打印类型：1图片；2文件',
  `pic_ids` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci COMMENT '打印图片',
  `file_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT '打印文件',
  `unit_prize` decimal(10,2) DEFAULT NULL COMMENT '20231216 单价',
  `page_count` int DEFAULT NULL COMMENT '20231216 计费页面数',
  `order_prize` decimal(10,2) DEFAULT NULL COMMENT '20231216 订单金额',
  `we_pub_openid` varchar(28) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '付款状态；0未付；1已付',
  `is_download` tinyint(1) DEFAULT '0' COMMENT '打印机读取状态；0未读取；1已读取',
  `is_print` tinyint(1) DEFAULT '0' COMMENT '打印上报状态；0未打印；1已打印',
  `is_get` tinyint(1) DEFAULT NULL COMMENT '领取状态：0待领取，1已领取',
  `printer_id` char(19) DEFAULT NULL COMMENT '20231215:打印机编号',
  `printer_ip` varchar(64) DEFAULT NULL COMMENT '20231215:客户端ip',
  `print_time` datetime DEFAULT NULL COMMENT '20231216:打印时间',
  `source` varchar(32) DEFAULT NULL,
  `sort` int DEFAULT '1000' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) DEFAULT '0' COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) DEFAULT '0' COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci COMMENT '备注',
  `creater` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='打印任务列表';

SET FOREIGN_KEY_CHECKS = 1;
