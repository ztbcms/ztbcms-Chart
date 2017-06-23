<?php

namespace Chart\Controller;

class TestController {
    function makeTestData(){
        $table = M('mirror');
        $site = [
            'www.a.com',
            'www.b.com',
            'www.c.com',
            'www.d.com',
        ];
        $now = time();

        for($i = 1;$i <= 100000; $i++){
            foreach ($site as $item){
                if(rand(100,999) % 8 == 0){
                    $status = 0;
                    $ping = -1;
                }else{
                    $status = 1;
                    $ping = rand(20,200);
                }

                $row = [
                    'time' => $now - ($i * 60 * 5),
                    'ping' => $ping,
                    'status' => $status,
                    'site' => $item
                ];

                $table->data($row)->add();
            }
        }
    }
}