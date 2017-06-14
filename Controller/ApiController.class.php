<?php

namespace Chart\Controller;

use Think\Controller;

/**
 * 对外公开的 API 接口
 * @package Chart\Controller
 */
class ApiController extends Controller {

    /**
     * 柱状图
     */
    const CHART_BAR = 1;
    /**
     * 折线图
     */
    const CHART_LINK = 2;
    /**
     * 饼图
     */
    const CHART_PIE = 3;

    /**
     * 获取图表
     */
    public function getChart() {

        //获取图表标识
        $token = I('get.token');

        $chart = M('chartList')->where(['token' => $token])->find();
        if (empty($chart)) {
            exit("图表不存在！");
        }

        //设置 X 轴数据
        self::setX($chart['table'], $chart['x'], $chart['x_type']);

        //设置 Y 轴数据
        self::setY($chart['table'], $chart['x'], $chart['y'], $chart['y_type']);

        //设置图表大小
        self::setSize(I('get.size', '600*400'));

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
    }

    /**
     * 设置 X 轴数据
     * @param $tableName string X轴数据获取表名
     * @param $x string 字段名
     * @param string $x_type string 统计方式
     * @return array|string
     */
    protected function setX($tableName, $x, $x_type = 'field') {
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

        $this->assign('x_data', $x_data);
        return $x_data;
    }

    /**
     * 设置 Y 轴数据
     * @param $tableName string Y轴数据获取表名
     * @param $x string X 轴字段名
     * @param $y string Y 轴字段名
     * @param string $y_type 统计方式
     * @return array|string
     */
    protected function setY($tableName, $x, $y, $y_type = 'count') {
        $y_data = [];

        $table = M($tableName);

        switch ($y_type) {
            case 'field':
                break;
            case 'count':
                $sql = 'select count(' . $y . ') as db_count from ' . C('DB_PREFIX') . $tableName . ' group by ' . $x;
                $y_set = $table->query($sql);
                foreach ($y_set as $item) {
                    $y_data[] = $item['db_count'];
                }
                $y_data = implode(',', $y_data);
                break;
        }

        $this->assign('y_data', $y_data);
        return $y_data;
    }

    /**
     * 设置图表的大小
     * @param string $size string 格式如 xxx*yyy
     */
    protected function setSize($size = '600*400') {
        //判断图表展示大小
        $size = explode('*', $size);
        $width = trim($size[0]);
        $height = trim($size[1]);
        $this->assign('width', $width);
        $this->assign('height', $height);
    }

    /**
     * 根据所选模式显示图表
     * @param int $type string 图表显示模式
     */
    protected function showChart($type = 1) {
        switch ($type) {
            case self::CHART_BAR:
                $this->display('bar');
                break;
            case self::CHART_LINK:
                $this->display('link');
                break;
            case self::CHART_PIE:
                $this->display('pie');
                break;
            default:
                $this->display('bar');
        }
    }

    /**
     * demo
     */
        public function getChartWithDebug() {
        //获取图表标识
        $token = I('get.token');

        $chart = M('chartList')->where(['token' => $token])->find();
        if (empty($chart)) {
            exit("图表不存在！");
        }

        //设置 X 轴数据
        var_dump(self::setX($chart['table'], $chart['x'], $chart['x_type']));

        //设置 Y 轴数据
        var_dump(self::setY($chart['table'], $chart['x'], $chart['y'], $chart['y_type']));

        //设置图表大小
        self::setSize(I('get.size', '600*400'));

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
    }
}