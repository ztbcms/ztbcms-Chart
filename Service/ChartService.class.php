<?php

namespace Chart\Service;

use Chart\Model\ChartModel;
use Think\Exception;

class ChartService {

    /**
     * 获取 X 轴数据
     * @param $tableName string X轴数据获取表名
     * @param $time_field string 时间字段
     * @param $x string 字段名
     * @param string $x_type 统计方式
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getX($tableName, $time_field, $x, $x_type = '__FIELD', $filter = '1=1', $order = 'id', $showAll = true) {

        $x_type = trim(strtoupper($x_type));

        if (empty($filter)) $filter = '1=1';

        $x_filter = ChartModel::X_TYPE[$x_type];

        if ($x_filter) {
            $x_filter = new FilterX();
            return $x_filter->$x_type($tableName, $time_field, $x, $x_type, $filter, $order, $showAll);
        } else {
            //没有找到方法
            throw_exception(new Exception('没有指定X轴筛选规则'));
        }
    }

    /**
     * 获取 Y 轴数据
     * @param $tableName string Y轴数据获取表名
     * @param $time_field string 时间字段
     * @param $x string X 轴
     * @param $x_type string X 轴类型
     * @param $y string Y 轴
     * @param string $y_type 统计方式
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getY($tableName, $time_field, $x, $x_type, $y, $y_type = '__COUNT', $filter = '1=1', $order = 'id', $showAll = true) {

        //获取真正的统计基准字段 X
        $x = static::getXField($x, $x_type);

        $y_type = trim(strtoupper($y_type));

        if (empty($filter)) $filter = '1=1';

        $y_filter = ChartModel::Y_TYPE[$y_type];

        if ($y_filter) {
            $y_filter = new FilterY();
            return $y_filter->$y_type($tableName, $time_field, $x, $x_type, $y, $y_type, $filter, $order, $showAll);
        } else {
            //没有找到方法
            throw_exception(new Exception('没有指定Y轴筛选规则'));
        }
    }

    /**
     * 获取统计基准字段
     * @param $x
     * @param $x_type
     * @return mixed
     */
    static function getXField($x, $x_type) {
        $x_type = trim(strtoupper($x_type));

        if ($x_type == "__SCRIPT") {
            $class = new $x();
            return $class->getField();
        } else {
            return $x;
        }
    }

    /**
     * 获取额外的字段筛选条件
     * @param $fields
     * @param $operators
     * @param $values
     * @return string
     */
    static function getFilter($fields, $operators, $values) {
        if (is_array($fields)) {
            $filter = [];
            foreach ($fields as $k => $v) {
                if (!empty($values[$k])) {
                    //对时间段筛选进行特殊处理
                    if ($k === 'during') {
                        $today = mktime(0, 0, 0, date('m'), date('d') + 1, date('y'));
                        $values[$k] = strtotime('- ' . $values[$k] . ' day', $today) . ' AND ' . $today;
                    }
                    $filter[] = self::concatFilter($v, $operators[$k], $values[$k]);
                }
            }
            return implode(' AND ', $filter);
        } elseif (!empty($filter)) {
            return self::concatFilter($fields, $operators, $values);
        } else {
            return '';
        }
    }

    /**
     * 根据操作符拼接 filter 字符串
     * @param $field
     * @param $operator
     * @param $value
     * @return string
     */
    protected function concatFilter($field, $operator, $value) {
        $operator = self::getOperator($operator);
        switch ($operator) {
            case 'IS NULL':
            case 'IS NOT NULL':
                $filter = $field . ' ' . $operator;
                break;
            case 'BETWEEN':
                $filter = $field . ' ' . $operator . ' ' . $value;
                break;
            default:
                $filter = $field . ' ' . $operator . ' \'' . $value . '\'';
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