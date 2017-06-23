<?php

namespace Chart\Lib;

abstract class BaseScriptY {
    /**
     * 获取统计结果或者基准结果集合
     *
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     *
     * @return string   格式应如："a,b,c,d,e,f,g"
     */
    abstract public function run($tableName, $time_field, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true);
}