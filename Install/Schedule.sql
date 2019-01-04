DROP TABLE IF EXISTS `cms_schedule`;
CREATE TABLE `cms_schedule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '默认规则名称',
  `target_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '关联類型',
  `target` int(15) NOT NULL COMMENT '类型id',
  `add_time` int(15) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 15 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `cms_schedule_rule`;
CREATE TABLE `cms_schedule_rule`  (
  `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` int(15) NOT NULL COMMENT '关联id',
  `start_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '开始时间',
  `end_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '结束时间',
  `year` int(4) NOT NULL DEFAULT 0 COMMENT '年',
  `month` tinyint(2) NOT NULL DEFAULT 0 COMMENT '月',
  `day` tinyint(2) NOT NULL DEFAULT 0 COMMENT '日',
  `week` tinyint(1) NOT NULL DEFAULT 0 COMMENT '星期',
  `loop_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '類型',
  `add_time` int(11) NOT NULL COMMENT '添加時間',
  `sort` int(10) NULL DEFAULT NULL COMMENT '排序 数值越大越先校验',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

