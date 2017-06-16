DROP TABLE IF EXISTS `cms_chart_list`;

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
  PRIMARY KEY (`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;
