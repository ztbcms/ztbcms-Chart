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

    <script src="//cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="//cdn.bootcss.com/vue/2.3.3/vue.js"></script>

    <script src="//cdn.bootcss.com/echarts/3.6.1/echarts.common.min.js"></script>

    <script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>

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
            <th>操作</th>
        </tr>
        <tr v-for="item in list">
            <td>{{ item.id }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.token }}</td>
            <td>
                <iframe :src="'{:U('Api/getChart')}&size=300*150&token=' + item.token"></iframe>
            </td>
            <td>
                <a :href="'{:U('Api/getChart')}&size=cover&token=' + item.token" type="_blank" class="btn btn-info">查看大图</a>
                <button class="btn btn-danger" @click="del(item)">删除</button>
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
            },
            del:function(item){
                $.post("{:U('Index/del')}",{
                    token:item.token,
                    auth:"{$chart_auth}"
                },function(res) {
                    if(res.status){
                        layer.msg('删除成功');
                    }else{
                        layer.msg('删除失败');
                    }
                    location.reload();
                },'json');
            }
        }
    })
</script>
</body>
</html>