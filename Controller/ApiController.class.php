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

    public function getChart() {
        $token = I('get.token');

        M('chartToken')->where(['token' => $token])->find();
    }

    public function getChartDemo() {
        $token = I('get.token');

        $chart = M('chartList')->where(['token' => $token])->find();

        if (empty($chart)) {
            exit("图表不存在！");
        }

        //分析 chart 的统计模式
        $table = M($chart['table']);
        switch ($chart['x_type']) {
            case 'field':
                $x = $table->field($chart['x'])->group($chart['x'])->select();
                $x_data = [];
                foreach ($x as $item) {
                    $x_data[] = $item[$chart['x']];
                }
                $x_data = implode(',', $x_data);
                break;
            case 'time':
                break;
        }

        var_dump($x_data);
        $this->assign('x_data', $x_data);

        switch ($chart['y_type']) {
            case 'field':
                break;
            case 'count':
                $sql = 'select count(' . $chart['y'] . ') as db_count from ' . C('DB_PREFIX') . $chart['table'] . ' group by ' . $chart['x'];
                $y = $table->query($sql);
                $y_data = [];
                foreach ($y as $item) {
                    $y_data[] = $item['db_count'];
                }
                $y_data = implode(',', $y_data);
                break;
        }
        var_dump($y_data);
        $this->assign('y_data', $y_data);

        //post 数据
//        $this->assign('x_data',"Mon,Tue,Wed,Thu,Fri,Sat,Sun");
//        $this->assign('y_data',"10,52,200,334,390,330,220");

        $type = I('get.type', '1');

        $size = I('get.size', '600*400', '');
        $size = explode('*', $size);
        $width = $size[0];
        $height = $size[1];

        $this->assign('width', $width);
        $this->assign('height', $height);

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
}