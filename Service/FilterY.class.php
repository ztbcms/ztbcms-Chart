<?php

namespace Chart\Service;

class FilterY {

    /**
     * 获取字段计数
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

    /**
     * 获取字段总数
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
    public function __SUM($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        var_dump($filter);

        $child = 'SELECT ' . $x . ',SUM(' . $y . ') AS db_sum FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_sum IS NULL,0,db_sum) AS db_sum FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_sum'];
        }
        return implode(',', $y_data);
    }

    /**
     * 获取字段平均值
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
    public function __AVG($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        $child = 'SELECT ' . $x . ',AVG(' . $y . ') AS db_avg FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_avg IS NULL,0,db_avg) AS db_avg FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_avg'];
        }
        return implode(',', $y_data);
    }

    /**
     * 获取字段最大值
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
    public function __MAX($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        $child = 'SELECT ' . $x . ',MAX(' . $y . ') AS db_max FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_max IS NULL,0,db_max) AS db_max FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_max'];
        }
        return implode(',', $y_data);
    }

    /**
     * 获取字段最小值
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
    public function __MIN($tableName, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true){
        $y_data = [];

        $table = M($tableName);

        $child = 'SELECT ' . $x . ',MIN(' . $y . ') AS db_min FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
        if ($showAll) {
            $sql = 'SELECT DISTINCT ' . $x . ',IF(db_min IS NULL,0,db_min) AS db_min FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
        } else {
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_min'];
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