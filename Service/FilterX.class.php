<?php

namespace Chart\Service;

use Think\Exception;

class FilterX {

    /**
     * 按字段统计
     *
     * @param        $tableName
     * @param        $time_field
     * @param        $time_section
     * @param        $x
     * @param string $x_type
     * @param string $x_time 时间段
     * @param string $x_script 脚本
     * @param string $x_foreign_table 关联表
     * @param string $x_foreign_key 关联字段
     * @param string $x_foreign_field 显示字段
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __FIELD(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type,
        $x_time,
        $x_script,
        $x_foreign_table,
        $x_foreign_key,
        $x_foreign_field,
        $filter,
        $order,
        $showAll
    ) {
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
     * 外键关联
     *
     * @param        $tableName
     * @param        $time_field
     * @param        $time_section
     * @param        $x
     * @param string $x_type
     * @param string $x_time 时间段
     * @param string $x_script 脚本
     * @param string $x_foreign_table 关联表
     * @param string $x_foreign_key 关联字段
     * @param string $x_foreign_field 显示字段
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __FOREIGN(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type,
        $x_time,
        $x_script,
        $x_foreign_table,
        $x_foreign_key,
        $x_foreign_field,
        $filter,
        $order,
        $showAll
    ) {
        $x_data = [];

        $table = M($tableName);

        $fullTableName = C('DB_PREFIX') . $tableName;

        $table
            ->join("INNER JOIN " . C('DB_PREFIX') . $x_foreign_table . ' as b ON ' . $fullTableName . '.' . $x . ' = b.' . $x_foreign_key)
            ->field('b.' . $x_foreign_field . ',' . $fullTableName . '.' . $x . ',b.' . $x_foreign_key)
            ->group($x)->order($order);

        if (!$showAll) {
            $table->where($filter);
        }

        $x_set = $table->select();

        foreach ($x_set as $item) {
            $x_data[] = $item[$x_foreign_field];
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
     * @param string $x_time 时间段
     * @param string $x_script 脚本
     * @param string $x_foreign_table 关联表
     * @param string $x_foreign_key 关联字段
     * @param string $x_foreign_field 显示字段
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __TIME(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type,
        $x_time,
        $x_script,
        $x_foreign_table,
        $x_foreign_key,
        $x_foreign_field,
        $filter,
        $order,
        $showAll
    ) {

        $group_time = '';

        $x_time = explode('-', strtoupper(trim($x_time)));

        switch ($x_time[0]) {
            case 'I':
                //按分钟统计
                $real_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m-%d %H:\'), ';

                $group1 = $group2 = range(0, 60, $x_time[1]);
                unset($group2[0]);
                $index = implode(',', $group1);


                if (60 % $x_time[1] !== 0) {
                    $group2[] = 60;
                    $index .= ',60';
                }

                $group = '\'' . implode('\',\'', array_map(function ($i, $j) {
                        if ($i < 10) $i = '0' . $i;
                        if ($j < 10) $j = '0' . $j;
                        return $i . '~' . $j;
                    }, $group1, $group2)) . '\'';

                $group_time = 'CONCAT (' . $real_time . 'ELT(INTERVAL (DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%i\'),' . $index . '),' . $group . ')) group_time ';
                break;
            case 'H':
                //按小时统计
                $real_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m-%d \'), ';

                $group1 = $group2 = range(0, 24, $x_time[1]);
                unset($group2[0]);
                $index = implode(',', $group1);

                if (24 % $x_time[1] !== 0) {
                    $group2[] = 24;
                    $index .= ',24';
                }

                $group = '\'' . implode('\',\'', array_map(function ($i, $j) {
                        return $i . '~' . $j;
                    }, $group1, $group2)) . '\'';

                $group_time = 'CONCAT (' . $real_time . 'ELT(INTERVAL (DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%H\'),' . $index . '),' . $group . ')) group_time ';
                break;
            case 'D':
                //按日统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m-%d\') group_time ';
                break;
            case 'M':
                //按月统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m\') group_time ';
                break;
            case 'Y':
                //按年统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y\') group_time ';
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
     * @param string $x_time 时间段
     * @param string $x_script 脚本
     * @param string $x_foreign_table 关联表
     * @param string $x_foreign_key 关联字段
     * @param string $x_foreign_field 显示字段
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    public function __SCRIPT(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type,
        $x_time,
        $x_script,
        $x_foreign_table,
        $x_foreign_key,
        $x_foreign_field,
        $filter,
        $order,
        $showAll
    ) {
        $sctipt = new $x_script();
        return $sctipt->run($tableName, $time_field, $time_section, $x, $x_type, $x_time, $x_script, $x_foreign_table, $x_foreign_key, $x_foreign_field, $filter, $order, $showAll);
    }
}