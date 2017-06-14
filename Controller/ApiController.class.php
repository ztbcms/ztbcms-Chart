<?php

namespace Chart\Controller;

use Think\Controller;
use Chart\Service\ChartService;

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
        $x_data = ChartService::getX($chart['table'], $chart['x'], $chart['x_type']);
        $this->assign('x_data', $x_data);

        //设置 Y 轴数据
        $y_data = ChartService::getY($chart['table'], $chart['x'], $chart['y'], $chart['y_type']);
        $this->assign('y_data', $y_data);

        //设置图表大小
        $size = ChartService::getSize(I('get.size', '600*400'));
        $this->assign('size', $size);

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
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
        $x_data = ChartService::getX($chart['table'], $chart['x'], $chart['x_type']);
        var_dump($x_data);
        $this->assign('x_data', $x_data);

        //设置 Y 轴数据
        $y_data = ChartService::getY($chart['table'], $chart['x'], $chart['y'], $chart['y_type']);
        var_dump($y_data);
        $this->assign('y_data', $y_data);

        //设置图表大小
        $size = ChartService::getSize(I('get.size', '600*400'));
        var_dump($size);
        $this->assign('size', $size);

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
    }

    /**
     * 根据所选模式显示图表
     * @param int $type string 图表显示模式
     */
    protected function showChart($type = 1) {
        switch ($type) {
            case self::CHART_BAR:
                $this->display('charts/bar');
                break;
            case self::CHART_LINK:
                $this->display('charts/link');
                break;
            case self::CHART_PIE:
                $this->display('charts/pie');
                break;
            default:
                $this->display('charts/bar');
        }
    }
}