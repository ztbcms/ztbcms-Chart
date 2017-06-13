DROP TABLE IF EXISTS `cms_chart_field`;

CREATE TABLE `cms_chart_field` (
  `id` INT(11) NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `x` TEXT COMMENT 'X 轴筛选字段',
  `y` TEXT COMMENT 'Y 轴筛选字段',
  `filter` TEXT COMMENT '可供选择的过滤器',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `cms_chart_list`;

CREATE TABLE `cms_chart_list` (
  `id` INT(11) NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `token` VARCHAR(255) NOT NULL COMMENT '图表唯一标识',
  `x` TEXT COMMENT 'X 轴筛选字段',
  `y` TEXT COMMENT 'Y 轴筛选字段',
  `filter` TEXT COMMENT '可供选择的过滤器',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;