<?php

namespace Chart\Lib;

abstract class BaseScriptY {
    /**
     * 获取统计结果或者基准结果集合
     *
     * @return string   格式应如："a,b,c,d,e,f,g"
     */
    abstract public function run();
}