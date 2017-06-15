<?php

namespace Chart\Script\X;

use Chart\Lib\BaseScript;

class GetDeviceRegionName extends BaseScript {

    public function getField() {
        return 'region_id';
    }

    public function run() {
        $x_data = [];
        $prefix = C('DB_PREFIX');
        $x_set = M('device a')
            ->field('a.id, CONCAT(province,city,region) as region_name')
            ->join('LEFT JOIN ' . $prefix . 'region b on a.region_id = b.id')
            ->group('region_id')
            ->order('id')
            ->select();

        foreach ($x_set as $item) {
            $x_data[] = $item['region_name'];
        }
        $x_data = implode(',', $x_data);
        return $x_data;
    }
}