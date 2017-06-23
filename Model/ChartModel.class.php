<?php

namespace Chart\Model;

use Think\Model;

class ChartModel extends Model {
    /**
     * 柱状图
     */
    const CHART_BAR = 1;
    /**
     * 折线图
     */
    const CHART_LINK = 2;
    /**
     * 饼图
     */
    const CHART_PIE = 3;

    /**
     * X 轴统计方式
     */
    const X_TYPE = [
        "__FIELD" => '字段',
        "__TIME" => '时间段',
        '__SCRIPT' => '使用脚本',
    ];

    /**
     * Y 轴统计方式
     */
    const Y_TYPE = [
        '__COUNT' => '字段计数',
        '__SUM' => '求和',
        '__AVG' => '平均值',
        '__MAX' => '最大值',
        '__MIN' => '最小值',
        '__SCRIPT' => '使用脚本',
    ];

    // 默认像素单位
    const DEFAULT_PIXEL = 'px';

    /**
     * 时间段
     */
    const DURING = [
        '__L3' => '最近3天',
        '__L7' => '最近7天',
        '__L15' => '最近15天',
        '__L30' => '最近30天',
        '__L365' => '最近一年',
        '__DAY' => '按日',
        '__WEEK' => '按周',
        '__MONTH' => '按月',
        '__YEAR' => '按年',
    ];
}