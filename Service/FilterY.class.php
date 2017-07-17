<?php

namespace Chart\Service;

use Think\Exception;

class FilterY {

    /**
     * 原字段输出
     *
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param $y_type
     * @param $filter
     * @param $order
     * @param $showAll
     * @return string
     */
    function __FIELD($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        if (stripos($y, ',')) {
            $fields = explode(',', $y);
        } else {
            $fields = $y;
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *,' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;

        } else {
            $child = 'SELECT ' . $y . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            $sql = $child . ' ORDER BY ' . $order;
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index];
            }
        }

        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;
    }

    /**
     * 获取字段计数
     * @param $tableName
     * @param $time_field
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    function __COUNT($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        $fields = explode(',', $y);
        $fields_sql = '';
        foreach ($fields as $field) {
            $fields_sql .= ',COUNT(' . $field . ') AS ' . $field . '_count';
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *' . $fields_sql . ',' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;

        } else {
            $child = 'SELECT ' . $x . $fields_sql . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $show_sql = '';
                foreach ($fields as $field) {
                    $show_sql .= ',IF( ' . $field . '_count IS NULL, 0,' . $field . '_count) AS ' . $field . '_count';
                }
                $sql = 'SELECT DISTINCT ' . $x . $show_sql . ' FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);

        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index . '_count'];
            }
        }

        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;
    }

    /**
     * 获取字段总数
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __SUM($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        $fields = explode(',', $y);
        $fields_sql = '';
        foreach ($fields as $field) {
            $fields_sql .= ',SUM(' . $field . ') AS ' . $field . '_sum';
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *' . $fields_sql . ',' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;
        } else {
            $child = 'SELECT ' . $x . $fields_sql . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $show_sql = '';
                foreach ($fields as $field) {
                    $show_sql .= ',IF( ' . $field . '_sum IS NULL, 0,' . $field . '_sum) AS ' . $field . '_sum';
                }
                $sql = 'SELECT DISTINCT ' . $x . $show_sql . ' FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index . '_sum'];
            }
        }
        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;
    }

    /**
     * 获取字段平均值
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __AVG($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        $fields = explode(',', $y);
        $fields_sql = '';
        foreach ($fields as $field) {
            $fields_sql .= ',AVG(' . $field . ') AS ' . $field . '_avg';
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *' . $fields_sql . ',' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;
        } else {
            $child = 'SELECT ' . $x . $fields_sql . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $show_sql = '';
                foreach ($fields as $field) {
                    $show_sql .= ',IF( ' . $field . '_avg IS NULL, 0,' . $field . '_avg) AS ' . $field . '_avg';
                }
                $sql = 'SELECT DISTINCT ' . $x . $show_sql . ' FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index . '_avg'];
            }
        }
        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;

    }

    /**
     * 获取字段最大值
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __MAX($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        $fields = explode(',', $y);
        $fields_sql = '';
        foreach ($fields as $field) {
            $fields_sql .= ',MAX(' . $field . ') AS ' . $field . '_max';
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *' . $fields_sql . ',' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;

        } else {
            $child = 'SELECT ' . $x . $fields_sql . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $show_sql = '';
                foreach ($fields as $field) {
                    $show_sql .= ',IF( ' . $field . '_max IS NULL, 0,' . $field . '_max) AS ' . $field . '_max';
                }
                $sql = 'SELECT DISTINCT ' . $x . $show_sql . ' FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index . '_max'];
            }
        }
        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;
    }

    /**
     * 获取字段最小值
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return string
     */
    public function __MIN($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $y_data = [];

        $fields = explode(',', $y);
        $fields_sql = '';
        foreach ($fields as $field) {
            $fields_sql .= ',MIN(' . $field . ') AS ' . $field . '_min';
        }

        $table = M($tableName);

        if ($x_type == "__TIME") {
            //对根据时间分组的统计进行特殊处理
            $group_time = self::__TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);

            $sql = 'SELECT *' . $fields_sql . ',' . $group_time;
            $sql .= 'FROM ' . C('DB_PREFIX') . $tableName . ' ';
            $sql .= 'WHERE (' . $filter . ')  ';
            $sql .= 'GROUP BY group_time ORDER BY ' . $order;

        } else {
            $child = 'SELECT ' . $x . $fields_sql . ' FROM ' . C('DB_PREFIX') . $tableName . ' WHERE (' . $filter . ') GROUP BY ' . $x;
            if ($showAll) {
                $show_sql = '';
                foreach ($fields as $field) {
                    $show_sql .= ',IF( ' . $field . '_min IS NULL, 0,' . $field . '_min) AS ' . $field . '_min';
                }
                $sql = 'SELECT DISTINCT ' . $x . $show_sql . ' FROM (' . $child . ') AS a RIGHT JOIN ' . C('DB_PREFIX') . $tableName . ' USING (' . $x . ') ORDER BY ' . $order;
            } else {
                $sql = $child . ' ORDER BY ' . $order;
            }
        }

        $y_set = $table->query($sql);
        foreach ($y_set as $item) {
            foreach ($fields as $index) {
                $y_data[$index][] = $item[$index . '_min'];
            }
        }
        foreach ($y_data as $k => $v) {
            $y_data[$k] = implode(',', $v);
        }
        return $y_data;
    }

    /**
     * 使用脚本
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    function __SCRIPT($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
        $sctipt = new $y();
        return $sctipt->run($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);
    }


    /**
     * @param $tableName
     * @param $time_field
     * @param $time_section
     * @param $x
     * @param $x_type
     * @param $x_time
     * @param $y
     * @param string $y_type
     * @param string $filter
     * @param string $order
     * @param bool $showAll
     * @return mixed
     */
    protected function __TIME($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll) {
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
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m-%d\') group_time, ';
                break;
            case 'M':
                //按月统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y-%m\') group_time, ';
                break;
            case 'Y':
                //按年统计
                $group_time = 'DATE_FORMAT(FROM_UNIXTIME(' . $x . '),\'%Y\') group_time, ';
                break;
            default:
                throw_exception(new Exception('暂不兼容的时间单位'));
        }

        return $group_time;
    }
}