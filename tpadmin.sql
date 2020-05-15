/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : yjadmin

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 15/05/2020 18:03:08
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yj_auth
-- ----------------------------
DROP TABLE IF EXISTS `tp_auth`;
CREATE TABLE `tp_auth`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '权限名称',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '权限规则',
  `parent_id` int(11) DEFAULT NULL COMMENT '父级id',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态：1开启0关闭',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '菜单图标',
  `sort` int(3) DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '权限表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yj_auth
-- ----------------------------
INSERT INTO `tp_auth` VALUES (1, '权限管理', '', 0, 1, NULL, 1588856020, 'Hui-iconfont-root', 0);
INSERT INTO `tp_auth` VALUES (2, '管理员管理', 'user', 1, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (3, '列表', 'user/index', 2, 1, NULL, 1589199629, '', 0);
INSERT INTO `tp_auth` VALUES (4, '新增', 'user/add', 2, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (5, '编辑', 'user/edit', 2, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (6, '删除', 'user/del', 2, 1, NULL, NULL, NULL, 2);
INSERT INTO `tp_auth` VALUES (7, '角色管理', 'role', 1, 1, NULL, NULL, NULL, 2);
INSERT INTO `tp_auth` VALUES (8, '列表', 'user/index', 7, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (9, '新增', 'user/add', 7, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (10, '编辑', 'user/edit', 7, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (11, '删除', 'user/del', 7, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (12, '权限管理', 'auth', 1, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (13, '列表', 'auth/index', 12, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (14, '新增', 'auth/add', 12, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (15, '编辑', 'auth/edit', 12, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (16, '删除', 'auth/del', 12, 1, NULL, NULL, NULL, 0);
INSERT INTO `tp_auth` VALUES (17, '常规管理', '', 0, 1, 1589116523, 1589116523, 'Hui-iconfont-system', 1);
INSERT INTO `tp_auth` VALUES (18, '个人设置', 'profile', 17, 1, 1589117016, 1589117016, '', 0);
INSERT INTO `tp_auth` VALUES (19, '查看', 'profile/index', 18, 1, 1589117711, 1589117711, '', 0);
INSERT INTO `tp_auth` VALUES (20, '修改密码', 'profile/edit', 18, 1, 1589117736, 1589117736, '', 0);

-- ----------------------------
-- Table structure for yj_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_role`;
CREATE TABLE `tp_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '角色名称',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态：1开启0关闭',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yj_role
-- ----------------------------
INSERT INTO `tp_role` VALUES (2, '管理员', 1, 1588683022, 1588768293);
INSERT INTO `tp_role` VALUES (3, '操作员', 1, 1588687509, 1588687509);

-- ----------------------------
-- Table structure for yj_role_auth
-- ----------------------------
DROP TABLE IF EXISTS `tp_role_auth`;
CREATE TABLE `tp_role_auth`  (
  `role_id` int(11) DEFAULT NULL COMMENT '角色id',
  `auth_id` int(11) DEFAULT NULL COMMENT '权限id'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '角色-权限关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yj_role_auth
-- ----------------------------
INSERT INTO `tp_role_auth` VALUES (3, 1);
INSERT INTO `tp_role_auth` VALUES (3, 2);
INSERT INTO `tp_role_auth` VALUES (3, 3);
INSERT INTO `tp_role_auth` VALUES (3, 4);
INSERT INTO `tp_role_auth` VALUES (3, 5);
INSERT INTO `tp_role_auth` VALUES (3, 6);
INSERT INTO `tp_role_auth` VALUES (3, 7);
INSERT INTO `tp_role_auth` VALUES (3, 8);
INSERT INTO `tp_role_auth` VALUES (3, 9);
INSERT INTO `tp_role_auth` VALUES (3, 10);
INSERT INTO `tp_role_auth` VALUES (3, 11);
INSERT INTO `tp_role_auth` VALUES (2, 1);
INSERT INTO `tp_role_auth` VALUES (2, 2);
INSERT INTO `tp_role_auth` VALUES (2, 3);
INSERT INTO `tp_role_auth` VALUES (2, 4);
INSERT INTO `tp_role_auth` VALUES (2, 5);
INSERT INTO `tp_role_auth` VALUES (2, 6);
INSERT INTO `tp_role_auth` VALUES (2, 7);
INSERT INTO `tp_role_auth` VALUES (2, 8);
INSERT INTO `tp_role_auth` VALUES (2, 9);
INSERT INTO `tp_role_auth` VALUES (2, 10);
INSERT INTO `tp_role_auth` VALUES (2, 11);
INSERT INTO `tp_role_auth` VALUES (2, 12);
INSERT INTO `tp_role_auth` VALUES (2, 13);
INSERT INTO `tp_role_auth` VALUES (2, 14);
INSERT INTO `tp_role_auth` VALUES (2, 15);
INSERT INTO `tp_role_auth` VALUES (2, 16);

-- ----------------------------
-- Table structure for yj_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '密码=md5(md5(密码)密码盐)',
  `salt` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '密码盐（随机生成）',
  `realname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '邮箱',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `is_super` tinyint(1) DEFAULT 0 COMMENT '是否是超级管理员',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yj_user
-- ----------------------------
INSERT INTO `tp_user` VALUES (1, 'admin', '296c344e352a95df55bc2b19c9427047', '3488', 'admin', NULL, NULL, 1587881100, 1587881100, 1);
INSERT INTO `tp_user` VALUES (2, '张三', 'f8cdf85d03d61f02aff7a20fffb4c442', 'b65a', NULL, '13900000000', 'zhangsan@163.com', 1588766797, 1588768575, 0);
INSERT INTO `tp_user` VALUES (3, '李四', '8250657868463ffab5262481d3fdae8e', '0b21', NULL, '13900000001', 'lisi@163.com', 1588768045, 1588768045, 0);

-- ----------------------------
-- Table structure for yj_user_role
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_role`;
CREATE TABLE `tp_user_role`  (
  `user_id` int(11) DEFAULT NULL COMMENT '管理员id',
  `role_id` int(11) DEFAULT NULL COMMENT '角色id'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin COMMENT = '管理员-角色关联表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of yj_user_role
-- ----------------------------
INSERT INTO `tp_user_role` VALUES (3, 3);
INSERT INTO `tp_user_role` VALUES (2, 2);
INSERT INTO `tp_user_role` VALUES (2, 3);

SET FOREIGN_KEY_CHECKS = 1;
