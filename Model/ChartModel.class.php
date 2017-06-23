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
     * 时间段,
     * I ：分钟
     * H ：小时
     * D ：天
     * W ：周
     * M ：月
     * Y ：年
     */
    const DURING = [
        'I-5' => '最近5分钟',
        'I-15' => '最近15分钟',
        'I-30' => '最近30分钟',
        'H-1' => '最近1小时',
        'H-3' => '最近3小时',
        'H-12' => '最近12小时',
        'D-1' => '最近1天',
        'D-3' => '最近3天',
        'D-7' => '最近7天',
        'D-15' => '最近15天',
        'M-1' => '最近一个月',
        'M-3' => '最近三个月',
        'M-6' => '最近半年',
        'Y-1' => '最近一年',
        'Y-3' => '最近一年'
    ];
}