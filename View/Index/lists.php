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

    <style>
        table, tr, th, td {
            text-align: center;
        }

        .endline {
            text-align: center;
            color: grey;
        }
    </style>

</head>
<body>

<div id="app" class="container-fluid">

    <div class="form-group">
        <label class="form-label">筛选</label>
        <div class="row">
            <div class="col-md-2">
                <input class="form-control" type="text" v-model="filter.title" placeholder="请输入标题">
            </div>
            <div class="col-md-2">
                <input class="form-control" type="text" v-model="filter.token" placeholder="请输入token">
            </div>
            <div class="col-md-2">
                <button @click="filterList" class="btn btn-success">确认</button>
            </div>
        </div>
    </div>

    <h2>图表列表</h2>
    <table class="table">
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
                <a :href="'{:U('Api/getChart')}&size=cover&token=' + item.token" target="_blank"
                   class="btn btn-info">查看大图</a>
                <button class="btn btn-danger" @click="del(item)">删除</button>
            </td>
        </tr>
    </table>

    <div class="endline">
        <p>别扯啦，到底了</p>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            list: [],
            page: 1,
            limit: 20,
            origin: [],
            dataEnd: false,
            wait: 3,
            filter: {
                title: '',
                token: ''
            }
        },
        mounted: function () {
            this.getList();
            window.onscroll = this.getMore;
        },
        methods: {
            getList: function () {
                let that = this;
                let post = {
                    page: that.page,
                    limit: that.limit
                };
                $.post("{:U('Index/getChartList')}", post, function (res) {
                    if (res.status) {
                        that.origin = that.origin.concat(res.data);
                        that.filterList();
                        that.page = that.page + 1;
                    } else {
                        that.dataEnd = true;
                    }
                }, 'json');
            },
            getMore: function () {
                if (!this.dataEnd) {
                    let that = this;
                    let limit = document.body.clientHeight - window.innerHeight;
                    let now = document.body.scrollTop;

                    if (now >= limit) {
                        console.log(now);
                        that.getList();
                    }
                }
            },
            del: function (item) {
                $.post("{:U('Index/del')}", {
                    token: item.token,
                    auth: "{$chart_auth}"
                }, function (res) {
                    if (res.status) {
                        layer.msg('删除成功');
                    } else {
                        layer.msg('删除失败');
                    }
                    location.reload();
                }, 'json');
            },
            filterList: function () {

                let filter = this.filter

                if (filter.token && filter.title) {
                    layer.msg('不能同时使用两个过滤器');
                }

                if (filter.token !== '') {
                    let result = [];
                    let regEx = new RegExp(filter.token);
                    for (let i in this.origin) {
                        if (regEx.test(this.origin[i].token)) {
                            result[i] = this.origin[i];
                        }
                    }
                    this.list = result;
                    return;
                }

                if (filter.title !== '') {
                    let result = [];
                    let regEx = new RegExp(filter.title);
                    for (let i in this.origin) {
                        if (regEx.test(this.origin[i].title)) {
                            result[i] = this.origin[i];
                        }
                    }
                    this.list = result;
                    return;
                }

                this.list = this.origin;
            }
        },
    })
</script>
</body>
</html>