<?php

namespace Chart\Controller;

use Common\Controller\AdminBase;
use Chart\Service\ChartService;

/**
 *  图表
 */
class IndexController extends AdminBase {
    /**
     * 首页，简介
     */
    public function index() {
        $this->display();
    }

    /**
     * 创建图表
     */
    public function create() {
        $this->display();
    }

    /**
     * 已创建图表列表
     */
    public function lists() {
        $this->display();
    }

    public function previewer(){
        $get = I('get.');

        //设置 X 轴数据
        $x_data = ChartService::getX($get['table'], $get['x'], $get['x_type']);
        $this->assign('x_data', $x_data);

        //设置 Y 轴数据
        $y_data = ChartService::getY($get['table'], $get['x'], $get['y'], $get['y_type']);
        $this->assign('y_data', $y_data);

        //$get
        $size = ChartService::getSize($get['size']);
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
