<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- 可选的 Bootstrap 主题文件（一般不用引入） -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="https://cdn.bootcss.com/vue/2.3.3/vue.js"></script>

    <script src="https://cdn.bootcss.com/echarts/3.6.1/echarts.common.min.js"></script>

    <style>
        html,body{
            height: 100%;
            width:100%;
        }
    </style>
</head>
<body>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="main" style="position:absolute;width: {$size[width]};height:{$size[height]};"></div>

<script>
    // 基于准备好的dom，初始化echarts实例
    app = echarts.init(document.getElementById('main'));

    option = {
        title: {
            text: '{$title}',
            subtext:"{$subtext}"
        },
        toolbox: {
            show: {$tool},
            feature: {
                dataView: {},
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        grid: {
            left: '3%',
            bottom: '10%',
            right: '10%',
            containLabel: true
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            data: "{$x_data}".split(','),
        },
        yAxis: {
            splitLine: {
                show: false
            }
        },
        dataZoom: [{
            show: true,
            start: 0,
            end: 100,
            type: 'slider',
            filterMode: 'filter'
        }],
        series: [
            <?php
            $tipsArray = explode(',', $tips);
            $k = 0;
            foreach ($y_data as $data) {
                echo "{ name: '$tipsArray[$k]', type: 'line', data: '$data'.split(',')},";
                $k++;
            }
            ?>
        ]
    }

    // 使用刚指定的配置项和数据显示图表。
    app.setOption(option);
</script>
</body>
</html>