<?php

namespace Chart\Service;

use Think\Exception;

class FilterX {

    /**
     * 按字段统计
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __FIELD($tableName, $time_field, $x, $x_type = '__FIELD', $filter = '1=1', $order = 'id', $showAll = true) {
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
     * 按时间分组数据
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __TIME($tableName, $time_field, $time_section, $x, $x_type, $filter, $order, $showAll) {

        $group_time = '';

        $x = explode('-', strtoupper(trim($x)));

        switch ($x[0]) {
            case 'I':
                //按分钟统计
                $real_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%Y-%m-%d %H:\'), ';

                $group1 = $group2 = range(0, 60, $x[1]);
                unset($group2[0]);
                $index = implode(',', $group1);


                if (60 % $x[1] !== 0) {
                    $group2[] = 60;
                    $index .= ',60';
                }

                $group = '\'' . implode('\',\'', array_map(function ($i, $j) {
                        return $i . '~' . $j;
                    }, $group1, $group2)) . '\'';

                $group_time = 'CONCAT (' . $real_time . 'ELT(INTERVAL (DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%i\'),' . $index . '),' . $group . ')) group_time ';
                break;
            case 'H':
                //按小时统计
                $real_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%Y-%m-%d \'), ';

                $group1 = $group2 = range(0, 24, $x[1]);
                unset($group2[0]);
                $index = implode(',', $group1);

                if (24 % $x[1] !== 0) {
                    $group2[] = 24;
                    $index .= ',24';
                }

                $group = '\'' . implode('\',\'', array_map(function ($i, $j) {
                        return $i . '~' . $j;
                    }, $group1, $group2)) . '\'';

                $group_time = 'CONCAT (' . $real_time . 'ELT(INTERVAL (DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%H\'),' . $index . '),' . $group . ')) group_time ';
                break;
            case 'D':
                //按日统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%Y-%m-%d\') group_time, ';
                break;
            case 'M':
                //按月统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%Y-%m\') group_time, ';
                break;
            case 'Y':
                //按年统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $time_field . '),\'%Y\') group_time, ';
                break;
            default:
                throw_exception(new Exception('暂不兼容的时间单位'));
        }
        $sql = 'SELECT *,' . $group_time;
        $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
        $sql .= 'WHERE (' . $filter . ')  ';
        $sql .= 'GROUP BY group_time ORDER BY ' . $order;

        $x_set = M($tableName)->query($sql);
        $x_data = [];

        foreach ($x_set as $item) {
            $x_data[] = $item['group_time'];
        }

        return implode(',', $x_data);
    }

    /**
     * 使用脚本统计
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param string $x_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    public function __SCRIPT($tableName, $time_field, $time_section, $x, $x_type, $filter, $order, $showAll) {
        $sctipt = new $x();
        return $sctipt->run($tableName, $time_field, $time_section, $x, $x_type, $filter, $order, $showAll);
    }
}