<?php

namespace Chart\Service;

class ChartService {

    /**
     * 获取 X 轴数据
     * @param $tableName string X轴数据获取表名
     * @param $x string 字段名
     * @param string $x_type string 统计方式
     * @return array|string
     */
    static function getX($tableName, $x, $x_type = 'field') {
        $x_data = [];

        $table = M($tableName);

        switch ($x_type) {
            case 'field':
                $x_set = $table->field($x)->group($x)->select();
                foreach ($x_set as $item) {
                    $x_data[] = $item[$x];
                }
                $x_data = implode(',', $x_data);
                break;
            case 'year':
                break;
            case 'month':
                break;
            case 'week':
                break;
            case 'day':
                break;
        }
        return $x_data;
    }

    /**
     * 获取 Y 轴数据
     * @param $tableName string Y轴数据获取表名
     * @param $x string X 轴字段名
     * @param $y string Y 轴字段名
     * @param string $y_type 统计方式
     * @return array|string
     */
    static function getY($tableName, $x, $y, $y_type = 'count') {
        $y_data = [];

        $table = M($tableName);

        switch ($y_type) {
            case 'count':
                $sql = 'select count(' . $y . ') as db_count from ' . C('DB_PREFIX') . $tableName . ' group by ' . $x;
                $y_set = $table->query($sql);
                foreach ($y_set as $item) {
                    $y_data[] = $item['db_count'];
                }
                $y_data = implode(',', $y_data);
                break;
        }

        return $y_data;
    }

    /**
     * 设置图表的大小
     * @param string $size string 格式如 xxx*yyy
     * @return array
     */
    static function getSize($size = '600*400') {
        //判断图表展示大小
        $size = explode('*', $size);

        return [
            'width' => trim($size[0]),
            'height' => trim($size[1])
        ];
    }

}