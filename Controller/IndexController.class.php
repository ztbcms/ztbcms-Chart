<?php

namespace Chart\Controller;

use Common\Controller\AdminBase;

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
     * 配置图表模块
     */
    public function config() {
        $this->display();
    }

    /**
     * 配置图表模块
     */
    public function doConfig() {
        $x = I('post.x');
        $y = I('post.y');
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
}
