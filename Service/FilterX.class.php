<?php

namespace Chart\Service;

class FilterX {

    /**
     * 按字段统计
     * @param $tableName
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __FIELD($tableName, $x, $x_type = 'field', $filter = '1=1', $order = 'id', $showAll = true) {
        $x_data = [];

        $table = M($tableName);

        if ($showAll) {
            $x_set = $table->field($x)->group($x)->order($order)->select();
        } else {
            $x_set = $table->where($filter)->field($x)->group($x)->order($order)->select();
        }

        foreach ($x_set as $item) {
            $x_data[] = $item[$x];
        }
        return implode(',', $x_data);
    }

    /**
     * 使用脚本统计
     * @param $tableName
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    public function __SCRIPT($tableName, $x, $x_type = 'field', $filter = '1=1', $order = 'id', $showAll = true){
        $sctipt = new $x();
        return $sctipt->run();
    }
}