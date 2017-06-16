<?php

namespace Chart\Service;

use Think\Exception;

class ChartService {

    // 统计总数
    const TYPE_COUNT = 'COUNT';

    // 按字段统计
    const TYPE_FIELD = 'FIELD';

    // 通过脚本统计
    const TYPE_SCRIPT = '__SCRIPT';

    // 默认像素单位
    const DEFAULT_PIXEL = 'px';

    /**
     * 获取 X 轴数据
     * @param $tableName string X轴数据获取表名
     * @param $x string 字段名
     * @param string $x_type 统计方式
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getX($tableName, $x, $x_type = 'field', $filter = '1=1', $order = 'id', $showAll = true) {
        $x_data = [];

        $table = M($tableName);

        $x_type = trim(strtoupper($x_type));

        if (empty($filter)) $filter = '1=1';

        switch ($x_type) {
            case self::TYPE_FIELD:
                if ($showAll) {
                    $x_set = $table->field($x)->group($x)->order($order)->select();
                } else {
                    $x_set = $table->where($filter)->field($x)->group($x)->order($order)->select();
                }

                foreach ($x_set as $item) {
                    $x_data[] = $item[$x];
                }
                $x_data = implode(',', $x_data);
                break;
            case self::TYPE_SCRIPT:
                $sctipt = new $x();
                $x_data = $sctipt->run();
                break;
            default:
                throw_exception(new Exception('没有指定X轴筛选规则'));
        }
        return $x_data;
    }

    /**
     * 获取 Y 轴数据
     * @param $tableName string Y轴数据获取表名
     * @param $x string X 轴
     * @param $x_type string X 轴类型
     * @param $y string Y 轴
     * @param string $y_type 统计方式
     * @param string $filter 额外的统计条件
     * @param string $order 排序方式
     * @param boolean $showAll 是否显示所有数据
     * @return array|string
     */
    static function getY($tableName, $x, $x_type, $y, $y_type = 'count', $filter = '1=1', $order = 'id', $showAll = true) {
        $y_data = [];

        $table = M($tableName);

        $x = static::getXField($x, $x_type);

        $y_type = trim(strtoupper($y_type));

        if (empty($filter)) $filter = '1=1';

        switch ($y_type) {
            case self::TYPE_COUNT:
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
                $y_data = implode(',', $y_data);
                break;
            case self::TYPE_SCRIPT:
                $sctipt = new $y();
                $y_data = $sctipt->run();
                break;
            default:
                throw_exception(new Exception('没有指定Y轴筛选规则'));
        }

        return $y_data;
    }

    /**
     * 获取统计基准字段
     * @return string
     */
    static function getXField($x, $x_type) {
        $field = '';

        $x_type = trim(strtoupper($x_type));

        switch ($x_type) {
            case self::TYPE_SCRIPT:
                $class = new $x();
                $field = $class->getField();
                break;
            case self::TYPE_FIELD:
                $field = $x;
        }

        return $field;
    }

    /**
     * 获取额外的字段筛选条件
     * @param $fields
     * @param $operators
     * @param $values
     * @return string
     */
    static function getXFilter($fields, $operators, $values) {
        if (is_array($fields)) {
            $filter = '';
            foreach ($fields as $k => $v) {
                $filter .= self::concatFilter($v, $operators[$k], $values[$k]);
            }
            return $filter;
        } else {
            return self::concatFilter($fields, $operators, $values);
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
                'width' => trim($size[0]) . self::DEFAULT_PIXEL,
                'height' => trim($size[1]) . self::DEFAULT_PIXEL
            ];
        }

    }

}