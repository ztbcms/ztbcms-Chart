<?php

namespace Chart\Lib;

abstract class BaseScript {
    /**
     * 返回基准字段
     * @return string
     */
    abstract public function getField();

    /**
     * 获取统计结果或者基准结果集合
     *
     * @return array    格式应如：['a','b','c','d','e']
     *          string   格式应如："a,b,c,d,e,f,g"
     */
    abstract public function run();
}