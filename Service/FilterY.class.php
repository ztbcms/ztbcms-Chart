<?php

namespace Chart\Service;

use Think\Exception;

class FilterY {

    /**
     * 获取字段计数
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    function __COUNT($tableName, $time_field, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,COUNT(' . $y . ') AS db_count,' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY  group_time desc, ' . $order;

        } else {
            $child = 'SELECT ' . $x . ',COUNT(' . $y . ') AS db_count FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $sql = 'SELECT DISTINCT ' . $x . ',IF(db_count IS NULL,0,db_count) AS db_count FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
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
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __SUM($tableName, $time_field, $x, $x_type, $y, $y_type = '__SUM', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,SUM(' . $y . ') AS db_sum, ' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY  group_time desc, ' . $order;
        } else {
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
    }

    /**
     * 获取字段平均值
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __AVG($tableName, $time_field, $x, $x_type, $y, $y_type = '__AVG', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,AVG(' . $y . ') AS db_avg, ' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY  group_time desc, ' . $order;
        } else {
            $child = 'SELECT ' . $x . ',AVG(' . $y . ') AS db_avg FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $sql = 'SELECT DISTINCT ' . $x . ',IF(db_avg IS NULL,0,db_avg) AS db_avg FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
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
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __MAX($tableName, $time_field, $x, $x_type, $y, $y_type = '__MAX', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,MAX(' . $y . ') AS db_max, ' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY  group_time desc, ' . $order;

        } else {
            $child = 'SELECT ' . $x . ',MAX(' . $y . ') AS db_max FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $sql = 'SELECT DISTINCT ' . $x . ',IF(db_max IS NULL,0,db_max) AS db_max FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
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
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __MIN($tableName, $time_field, $x, $x_type, $y, $y_type = '__MIN', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,MIN(' . $y . ') AS db_min, ' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY  group_time desc, ' . $order;

        } else {
            $child = 'SELECT ' . $x . ',MIN(' . $y . ') AS db_min FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $sql = 'SELECT DISTINCT ' . $x . ',IF(db_min IS NULL,0,db_min) AS db_min FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            $y_data[] = $item['db_min'];
        }

        echo $sql;
        return implode(',', $y_data);
    }

    /**
     * 使用脚本
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    function __SCRIPT($tableName, $time_field, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true) {
        $sctipt = new $y();
        return $sctipt->run($tableName, $time_field, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true);
    }


    /**
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    protected function __TIME($tableName, $time_field, $x, $x_type, $y, $y_type, $filter = '1=1', $order = 'id', $showAll = true) {
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

        return $group_time;
    }
}