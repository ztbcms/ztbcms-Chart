<?php

namespace Chart\Controller;

use Common\Controller\AdminBase;
use Chart\Service\ChartService;
use Chart\Model\ChartModel;

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
        $this->assign('tables', self::getModels());
        $this->assign('xScript',self::getXScripts());
        $this->assign('yScript',self::getYScripts());
        $this->display();
    }

    /**
     *  创建图表
     */
    public function doCreate() {
        $post = I('post.');
        $post['token'] = md5(json_encode($post));
        $chart = M('chartList')->where(['token' => $post['token']])->find();

        if ($chart) {
            $this->ajaxReturn(self::createReturn(true, $chart));
        } else {
            $status = M('chartList')->data($post)->add();
            if ($status > 0) {
                $post['id'] = $status;
                $this->ajaxReturn(self::createReturn(true, $post));
            } else {
                $this->ajaxReturn(self::createReturn(false));
            }
        }

    }

    /**
     * 获取模型
     * @return mixed
     */
    public function getModels(){
        return M('model')->select();
    }

    /**
     * 获取脚本
     * @return array
     */
    public function getXScripts() {
        $scriptDir = new \Dir(APP_PATH . '/Chart/Script/X');
        $scriptList = $scriptDir->toArray();
        $scripts = [];

        //遍历模块
        foreach ($scriptList as $item) {
            $script_classname = str_replace('.class.php', '', $item['filename']);
            $scripts[] = "\\Chart\\Script\\X\\" . $script_classname;
        }

        return $scripts;
    }

    /**
     * 获取脚本
     * @return array
     */
    public function getYScripts() {
        $scriptDir = new \Dir(APP_PATH . '/Chart/Script/Y');
        $scriptList = $scriptDir->toArray();
        $scripts = [];

        //遍历模块
        foreach ($scriptList as $item) {
            $script_classname = str_replace('.class.php', '', $item['filename']);
            $scripts[] = "\\Chart\\Script\\Y\\" . $script_classname;
        }

        return $scripts;

    }

    /**
     * 已创建图表列表
     */
    public function lists() {
        $this->display();
    }

    /**
     * 图表预览
     */
    public function previewer() {
        $get = I('get.');

        //设置 X 轴数据
        $x_data = ChartService::getX($get['table'], $get['x'], $get['x_type']);
        $this->assign('x_data', $x_data);

        //设置 Y 轴数据
        $y_data = ChartService::getY($get['table'], $get['x'], $get['x_type'], $get['y'], $get['y_type']);
        $this->assign('y_data', $y_data);

        //设置图表大小
        $size = ChartService::getSize($get['size']);
        $this->assign('size', $size);

        //设置图表标题
        $this->assign('title', $get['title']);

        //设置提示
        $this->assign('tips', $get['tips']);

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
    }

    /**
     * 根据所选模式显示图表
     * @param int $type string 图表显示模式
     */
    protected function showChart($type = 1) {
        switch ($type) {
            case ChartModel::CHART_BAR:
                $this->display('charts/bar');
                break;
            case ChartModel::CHART_LINK:
                $this->display('charts/link');
                break;
            case ChartModel::CHART_PIE:
                $this->display('charts/pie');
                break;
            default:
                $this->display('charts/bar');
        }
    }

    /**
     * 获取图表列表
     */
    public function getChartList() {
        $chartList = M('chartList')->select();
        $this->ajaxReturn(self::createReturn(true, $chartList));
    }

    /**
     * 获取表格的字段
     */
    public function getTableFields() {
        $table = I('get.table');
        $model = M($table);
        $fields = $model->getDbFields();
        $this->ajaxReturn(self::createReturn(true, $fields));
    }
}
