<?php

namespace Chart\Lib;

abstract class BaseScriptX {
    /**
     * 返回基准字段
     * @return string
     */
    abstract public function getField();

    /**
     * 获取统计结果或者基准结果集合
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     *
     * @return string   格式应如："a,b,c,d,e,f,g"
     */
    abstract public function run($tableName, $time_field, $x, $x_type = '__TIME', $filter = '1=1', $order = 'id', $showAll = true);
}