DROP TABLE IF EXISTS `cms_chart_list`;

CREATE TABLE `cms_chart_list` (
  `id`      INT(11)       NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `title`    VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '图表名称',
  `token`   VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '图表唯一标识',
  `table`   VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '统计表',
  `x`       VARCHAR(255)  NOT NULL DEFAULT '' COMMENT 'X 轴筛选字段',
  `x_type`  VARCHAR(255)  NOT NULL DEFAULT 'field' COMMENT 'X 轴筛选类型',
  `y`       VARCHAR(255)  NOT NULL DEFAULT '' COMMENT 'Y 轴筛选字段',
  `y_type`  VARCHAR(255)  NOT NULL DEFAULT 'count' COMMENT 'Y 轴筛选类型',
  `filter`  VARCHAR(255)  DEFAULT '' COMMENT '可供选择的过滤器',
  `tips`    VARCHAR(255)  DEFAULT '' COMMENT '提示信息',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `cms_chart_list` (`id`, `name`, `token`, `table`, `x`, `x_type`, `y`, `y_type`, `filter`, `tips`) VALUES ('1', '示例图表', 'a9a41bec8365599185c729e2047ae114', 'area_city', 'parentid', 'field', 'id', 'count', NULL, '城市数');
