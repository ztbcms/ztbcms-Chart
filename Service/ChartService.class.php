<?php

namespace Chart\Service;

use Chart\Model\ChartModel;
use Think\Exception;

class ChartService {

    /**
     * 获取 X 轴数据
     * @param $tableName string X轴数据获取表名
     * @param $time_field string 时间字段
     * @param $time_section string 时间区间
     * @param $x string 字段名
     * @param string $x_type 统计方式
     * @param string $x_script 脚本
     * @param string $x_foreign_table 关联表
     * @param string $x_foreign_key 关联字段
     * @param string $x_foreign_field 显示字段
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getX(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type = '__FIELD',
        $x_script = '',
        $x_foreign_table = '',
        $x_foreign_key = '',
        $x_foreign_field = '',
        $filter = '1=1',
        $order = 'id',
        $showAll = true
    ) {

        $x_type = trim(strtoupper($x_type));

        if (empty($filter)) $filter = '1=1';
        if ($time_section) $filter .= self::getTimeFilter($time_field, $time_section);

        $x_type_arr = [
            "__FIELD" => '按原字段分组',
            "__TIME" => '按时间段分组',
            '__FOREIGN' => '使用关联表字段分组',
            '__SCRIPT' => '使用脚本分组',
        ];
        $x_filter = $x_type_arr[$x_type];

        if ($x_filter) {
            $x_filter = new FilterX();
            return $x_filter->$x_type($tableName, $time_field, $time_section, $x, $x_type, $x_script, $x_foreign_table, $x_foreign_key, $x_foreign_field, $filter, $order, $showAll);
        } else {
            //没有找到方法
            throw_exception(new Exception('没有指定X轴筛选规则'));
        }
    }

    /**
     * 获取 Y 轴数据
     * @param $tableName string Y轴数据获取表名
     * @param $time_field string 时间字段
     * @param $time_section string 时间区间
     * @param $x string X 轴
     * @param $x_type string X 轴类型
     * @param $x_time string X 轴时间
     * @param $y string Y 轴
     * @param string $y_type 统计方式
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getY(
        $tableName,
        $time_field,
        $time_section,
        $x,
        $x_type,
        $x_time,
        $y,
        $y_type = '__COUNT',
        $filter = '1=1',
        $order = 'id',
        $showAll = true
    ) {

        $y_type = trim(strtoupper($y_type));

        if (empty($filter)) $filter = '1=1';
        if ($time_section) $filter .= self::getTimeFilter($time_field, $time_section);

        $y_type_arr = [
            '__FIELD' => '原字段输出',
            '__COUNT' => '字段计数',
            '__SUM' => '求和',
            '__AVG' => '平均值',
            '__MAX' => '最大值',
            '__MIN' => '最小值',
            '__SCRIPT' => '使用脚本',
        ];
        $y_filter = $y_type_arr[$y_type];

        if ($y_filter) {
            $y_filter = new FilterY();
            return $y_filter->$y_type($tableName, $time_field, $time_section, $x, $x_type, $x_time, $y, $y_type, $filter, $order, $showAll);
        } else {
            //没有找到方法
            throw_exception(new Exception('没有指定Y轴筛选规则'));
        }
    }

    /**
     * 获取额外的字段筛选条件
     * @param $table
     * @param $fields
     * @param $operators
     * @param $values
     * @return string
     */
    static function getFilter($table, $fields, $operators, $values) {
        if (is_array($fields)) {
            $filter = [];
            foreach ($fields as $k => $v) {
                if (!empty($values[$k])) {
                    $filter[] = self::concatFilter($table, $v, $operators[$k], $values[$k]);
                }
            }
            return implode(' AND ', $filter);
        } elseif (!empty($filter)) {
            return self::concatFilter($table, $fields, $operators, $values);
        } else {
            return '1=1';
        }
    }

    /**
     * 获取数据时间区间
     * @param $time_field
     * @param $time_section
     * @return string
     */
    protected function getTimeFilter($time_field, $time_section) {

        if (empty($time_section)) {
            return '';
        }

        $during = explode('-', strtoupper(trim($time_section)));
        $today = mktime(0, 0, 0, date('m'), date('d') + 1, date('y'));
        $now = time();
        $complex = $during[1] > 1 ? 's' : '';
        switch ($during[0]) {
            //分钟
            case 'I':
                $during[1] = strtotime('- ' . $during[1] . ' minute' . $complex, $now) . ' AND ' . $now;
                break;
            //小时
            case 'H':
                $during[1] = strtotime('- ' . $during[1] . ' hour' . $complex, $now) . ' AND ' . $now;
                break;
            //天
            case 'D':
                $during[1] = strtotime('- ' . $during[1] . ' day' . $complex, $today) . ' AND ' . $today;
                break;
            //周
            case 'W':
                $during[1] = strtotime('- ' . $during[1] . ' week' . $complex, $today) . ' AND ' . $today;
                break;
            //月
            case 'M':
                $during[1] = strtotime('- ' . $during[1] . ' month' . $complex, $today) . ' AND ' . $today;
                break;
            //年
            case 'Y':
                $during[1] = strtotime('- ' . $during[1] . ' year' . $complex, $today) . ' AND ' . $today;
                break;
            default:
                throw_exception(new Exception('暂不支持的时间区间'));
        }

        return ' AND ' . $time_field . ' BETWEEN ' . $during[1];
    }

    /**
     * 根据操作符拼接 filter 字符串
     * @param $table
     * @param $field
     * @param $operator
     * @param $value
     * @return string
     */
    protected function concatFilter($table, $field, $operator, $value) {
        $operator = self::getOperator($operator);
        switch ($operator) {
            case 'IS NULL':
            case 'IS NOT NULL':
                $filter = C('DB_PREFIX') . $table . '.' . $field . ' ' . $operator;
                break;
            case 'LIKE':
                $filter = C('DB_PREFIX') . $table . '.' . $field . ' ' . $operator . ' \'%' . $value . '%\'';
                break;
            case 'BETWEEN':
                $value = implode(' AND ', explode(',', $value));
                $filter = C('DB_PREFIX') . $table . '.' . $field . ' ' . $operator . ' ' . $value;
                break;
            default:
                $filter = C('DB_PREFIX') . $table . '.' . $field . ' ' . $operator . ' \'' . $value . '\'';
        }
        return $filter;
    }

    /**
     * 转换操作符
     * @param $operator
     * @return string
     */
    protected function getOperator($operator) {
        $operator = trim(strtoupper($operator));
        switch ($operator) {
            case 'EQ':
                $operator = '=';
                break;
            case 'NEQ':
                $operator = '!=';
                break;
            case 'GT':
                $operator = '>';
                break;
            case 'GET':
                $operator = '>=';
                break;
            case 'LT':
                $operator = '<';
                break;
            case 'LET':
                $operator = '<=';
                break;
        }
        return $operator;
    }

    /**
     * 设置图表的大小
     * @param string $size string 格式如 xxx*yyy
     * @return array
     */
    static function getSize($size = '600*400') {
        if (empty($size)) $size = '600*400';

        //判断图表展示大小
        if ($size == 'cover') {
            return [
                'width' => '100%',
                'height' => '100%'
            ];
        } else {
            $size = explode('*', $size);
            return [
                'width' => trim($size[0]) . ChartModel::DEFAULT_PIXEL,
                'height' => trim($size[1]) . ChartModel::DEFAULT_PIXEL
            ];
        }

    }

}