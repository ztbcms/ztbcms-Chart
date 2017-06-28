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
        html, body {
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="main" style="position:absolute;width: {$size[width]};height:{$size[height]};"></div>

<script>
    x_data = "{$x_data}".split(',');
    y_data = "{$y_data}".split(',');
    data = [];
    y_data.forEach(function (y, index) {
        data.push({
            name: x_data[index],
            value: y
        });
    });
</script>
<script>
    // 基于准备好的dom，初始化echarts实例
    app = echarts.init(document.getElementById('main'));

    option = {
        title: {
            text: '{$title}',
            subtext: '{$subtext}',
            x: 'center'
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
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        series: [
            {
                name: '{$tips}',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: data,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };


    // 使用刚指定的配置项和数据显示图表。
    app.setOption(option);
</script>
</body>
</html>