<?php
namespace Chart\Model;

use Think\Model;

class ChartModel extends Model{
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
        '__SCRIPT' => '使用脚本',
    ];

    /**
     * Y 轴统计方式
     */
    const Y_TYPE = [
        '__COUNT' => '字段计数',
        '__AMOUNT' => '求和',
        '__AVG' => '平均值',
        '__MAX' => '最大值',
        '__MIN' => '最小值',
        '__SCRIPT' => '使用脚本',
    ];

    // 默认像素单位
    const DEFAULT_PIXEL = 'px';
}