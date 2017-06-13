<?php

namespace Chart\Controller;

use Think\Controller;

/**
 * 对外公开的 API 接口
 * @package Chart\Controller
 */
class ApiController extends Controller{

    public function getChart(){
        $token = I('get.token');

        M('chartToken')->where(['token' => $token])->find();
    }

    public function getChartDemo(){
        $token = I('get.token');
        $this->display('chart');
    }
}