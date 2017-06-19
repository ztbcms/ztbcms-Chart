<?php

namespace Chart\Service;

class FilterY {

    /**
     * 获取字段统计
     * @param $tableName
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    function __COUNT($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        $child = 'SELECT ' . $x . ',COUNT(' . $y . ') AS db_count FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_count IS NULL,0,db_count) AS db_count FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_count'];
        }
        return implode(',', $y_data);
    }

    public function __AMOUNT($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        $child = 'SELECT ' . $x . ',COUNT(' . $y . ') AS db_count FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_count IS NULL,0,db_count) AS db_count FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_count'];
        }
        return implode(',', $y_data);
    }

    /**
     * 使用脚本
     * @param $tableName
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    function __SCRIPT($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $sctipt = new $y();
        return $sctipt->run();
    }
}