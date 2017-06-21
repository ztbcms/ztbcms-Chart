<?php

namespace Chart\Controller;

use Think\Controller;
use Chart\Service\ChartService;
use Chart\Model\ChartModel;

/**
 * 对外公开的 API 接口
 * @package Chart\Controller
 */
class ApiController extends Controller {

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

        self::getData($chart);

        //设置图表标题
        $this->assign('title', $chart['title']);

        //设置提示
        $this->assign('tips', $chart['tips']);

        /**
         * 路由参数
         */

        //设置图表大小
        $size = ChartService::getSize(I('get.size', '600*400'));
        $this->assign('size', $size);

        //是否显示工具，默认不显示
        $this->assign('tool', I('get.tool', '0'));

        //判断图表显示类型
        self::showChart(I('get.type', '1'));
    }

    /**
     * @param array $chart 图表记录
     * @param bool $focusUpdate 是否强制重新统计
     */
    protected function getData($chart, $focusUpdate = false) {

        //如果开启了缓存
        if (!$focusUpdate && $chart['cache']) {
            //检查缓存是否有效
            $now = time();
            $exp = strtotime("+ " . $chart['cache'] . " minute", $chart['cache_time']);
            if ($exp > $now) {
                self::getDataFromCache($chart);
                return;
            }
        }

        //设置 X 轴数据
        $x_data = ChartService::getX($chart['table'], $chart['x'], $chart['x_type'], $chart['filter'], $chart['order'], $chart['show_all']);
        $this->assign('x_data', $x_data);

        //设置 Y 轴数据
        $y_data = ChartService::getY($chart['table'], $chart['x'], $chart['x_type'], $chart['y'], $chart['y_type'], $chart['filter'], $chart['order'], $chart['show_all']);
        $this->assign('y_data', $y_data);

        //如果开启了数据缓存
        if ($chart['cache']) {
            //缓存数据
            $chart['cache_data'] = json_encode([
                "x" => $x_data,
                "y" => $y_data
            ]);
            //更新缓存时间
            $chart['cache_time'] = time();

            M('chartList')->data($chart)->save();
        }

        $this->assign('subtext', "更新于" . date('Y-m-d H:i:s', time()));
    }

    /**
     * 从缓存数据里获取统计数据
     * @param $chart
     */
    protected function getDataFromCache($chart) {
        $data = json_decode($chart['cache_data'], true);

        $this->assign('x_data', $data['x']);
        $this->assign('y_data', $data['y']);
        $this->assign('subtext', "更新于" . date('Y-m-d H:i:s', $chart['cache_time']));
    }

    /**
     * 手动更新缓存
     */
    public function updateCache() {
        //获取图表标识
        $token = I('get.token');

        $chart = M('chartList')->where(['token' => $token])->find();
        if (empty($chart)) {
            exit("图表不存在！");
        }

        self::getData($chart, true);

        $this->ajaxReturn(createReturn(true));
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
}