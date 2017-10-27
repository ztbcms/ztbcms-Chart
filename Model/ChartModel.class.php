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

    // 默认像素单位
    const DEFAULT_PIXEL = 'px';
}