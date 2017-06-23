<?php

namespace Chart\Script\X;

use Chart\Lib\BaseScriptX;

class GetProvince extends BaseScriptX {

    public function getField() {
        return 'parentid';
    }

    public function run($tableName, $x, $x_type = '__TIME', $filter = '1=1', $order = 'id', $showAll = true) {
        $x_data = [];
        $prefix = C('DB_PREFIX');
        $model = M('area_city a');
        $x_set = $model
            ->field('a.id, b.shortname as province_name')
            ->join('LEFT JOIN ' . $prefix . 'area_province b on a.parentid = b.id')
            ->group('a.parentid')
            ->order('id')
            ->select();

        foreach ($x_set as $item) {
            $x_data[] = $item['province_name'];
        }

        $x_data = implode(',', $x_data);
        return $x_data;
    }
}