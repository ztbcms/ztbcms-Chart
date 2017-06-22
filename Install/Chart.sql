DROP TABLE IF EXISTS `jsq_chart_list`;

CREATE TABLE `cms_chart_list` (
  `id`      INT(11)       NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `title`   VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '图表名称',
  `token`   VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '图表唯一标识',
  `table`   VARCHAR(255)  NOT NULL DEFAULT '' COMMENT '统计表',
  `x`       VARCHAR(255)  NOT NULL DEFAULT '' COMMENT 'X 轴筛选字段',
  `x_type`  VARCHAR(255)  NOT NULL DEFAULT 'field' COMMENT 'X 轴筛选类型',
  `y`       VARCHAR(255)  NOT NULL DEFAULT '' COMMENT 'Y 轴筛选字段',
  `y_type`  VARCHAR(255)  NOT NULL DEFAULT 'count' COMMENT 'Y 轴筛选类型',
  `order`   VARCHAR(255)  NOT NULL DEFAULT 'id' COMMENT '排序方式',
  `filter`  VARCHAR(255)  DEFAULT '' COMMENT '可供选择的过滤器',
  `tips`    VARCHAR(255)  DEFAULT '' COMMENT '提示信息',
  `show_all` TINYINT DEFAULT '1' COMMENT '是否显示所有数据（包含0）',
  `cache` VARCHAR(10) DEFAULT '0' COMMENT  '统计数据缓存时间,单位分钟',
  `cache_time` VARCHAR(255) DEFAULT '0' COMMENT '最近缓存时间',
  `cache_data` TEXT COMMENT '统计数据缓存',
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO `jsq_chart_list` (`id`, `title`, `token`, `table`, `x`, `x_type`, `y`, `y_type`, `order`, `filter`, `tips`, `show_all`) VALUES ('1', '各省份下市数量统计', '53b3867139cab440527b1c72de24dfcd', 'area_city', 'parentid', 'field', 'id', 'count', 'id', '', '城市数', '1');
INSERT INTO `jsq_chart_list` (`id`, `title`, `token`, `table`, `x`, `x_type`, `y`, `y_type`, `order`, `filter`, `tips`, `show_all`) VALUES ('2', '各省份下城市数量统计', 'fdd91a72b74b91b8dd08063e6eb193b7', 'area_city', '\\Chart\\Script\\X\\GetProvince', '__script', 'id', 'count', 'id', '', '城市数', '0');

