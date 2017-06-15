<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

</head>
<body>

<div id="app">
    <table class="table">
        <caption>图表列表</caption>
        <tr>
            <th>id</th>
            <th>图表名</th>
            <th>token</th>
            <th>预览</th>
        </tr>
        <tr v-for="item in list">
            <td>{{ item.id }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.token }}</td>
            <td>
                <iframe :src="'{:U('Api/getChart')}&size=300*150&token=' + item.token"></iframe>
            </td>
        </tr>
    </table>
</div>

<script>
    new Vue({
        el:'#app',
        data:{
            list:[],
            page:1,
            limit:20,
        },
        mounted:function(){
            this.getList();
        },
        methods:{
            getList:function(){
                let that = this;
                let post = {
                    page: that.page,
                    limit: that.limit
                };
                $.post("{:U('Index/getChartList')}",post,function(res){
                    that.list = res.data;
                },'json');
            }
        }
    })
</script>
</body>
</html>