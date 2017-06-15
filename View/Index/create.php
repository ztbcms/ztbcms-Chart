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

    <script src="//cdn.bootcss.com/vue/2.3.3/vue.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>

</head>
<body>

<div id="app" class="container-fluid" style="margin-top: 2rem;">
    <form class="form">
        <div class="form-group">
            <label class="form-label" for="">图表名称</label>
            <div class="row">
                <div class="col-md-4">
                    <input class="form-control" type="text" name="title" v-model="options.title" id="title"
                           placeholder="请输入图表名称">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="">数据来源表</label>
            <div class="row">
                <div class="col-md-4">
                    <input class="form-control" type="text" v-model="options.table" v-if="other_table" placeholder="请填写表名，不带前缀">
                    <select class="form-control" name="table" id="table" v-model="options.table" v-else>
                        <option value="">选择模型表</option>
                        <volist name="tables" id="table">
                            <option value="{$table['tablename']}">{$table[name]}</option>
                        </volist>
                    </select>
                    <label for="other_table" style="margin-top: 5px;">
                        <input type="checkbox" v-model="other_table" id="other_table">没有列出我需要的数据表，我需要手工填写
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">请选择统计基准（X 轴）</label>

            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="x_type" id="x_type" v-model="options.x_type">
                        <option value="">选择统计方式</option>
                        <option value="field">字段</option>
                        <option value="__script">脚本</option>
                    </select>
                </div>

                <div class="col-md-4" v-if="options.x_type == 'field'">
                    <select class="form-control" name="x" id="x_field" v-model="options.x">
                        <option value="">请选择字段</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-4" v-if="options.x_type == '__script'">
                    <select class="form-control" name="x" id="x_script" v-model="options.x">
                        <option value="">请选择脚本</option>
                        <volist name="xScript" id="item">
                            <option value="{$item}">{$item}</option>
                        </volist>
                    </select>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="form-label" for="">统计数（Y 轴）</label>
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="y_type" id="y_type" v-model="options.y_type">
                        <option value="">选择统计方式</option>
                        <option value="count">字段（计数总数）</option>
                        <option value="__script">脚本</option>
                    </select>
                </div>

                <div class="col-md-4" v-if="options.y_type == 'count'">
                    <select class="form-control" name="y" id="y_field" v-model="options.y">
                        <option value="">请选择字段</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-4" v-if="options.y_type == '__script'">
                    <select class="form-control" name="y" id="y_script" v-model="options.y"
                    >
                        <option value="">请选择脚本</option>
                        <volist name="yScript" id="item">
                            <option value="{$item}">{$item}</option>
                        </volist>
                    </select>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="form-label" for="">鼠标悬浮提示</label>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="tips" id="tips" v-model="options.tips"
                           placeholder="请输入图表悬浮提示">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">排序方式</label>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="order" id="order" v-model="options.order"
                           placeholder="请输入排序方式">
                </div>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-info" type="button" @click="makePreviewer">生成预览</button>

            <button class="btn btn-success" type="button" @click="createChart">生成图表</button>
        </div>

        <div class="form-group">
            <div class="well well-lg" id="url" v-if="url != ''">
                生成成功！图表链接为：{{ url }}
            </div>
        </div>

    </form>

    <div v-if="preview">
        <iframe :src="previewUrl" frameborder="0" :width="width" :height="height"></iframe>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            base: "{:U('Chart/Index/previewer')}",
            preview: false,
            previewUrl: '',
            fields: [],
            other_table:false,
            width: '900',
            height: '400',
            options: {
                title: '',
                table: '',
                x: '',
                x_type: '',
                y: '',
                y_type: '',
                tips: '',
                order:'id'
            },
            url: ''
        },
        methods: {
            getUrl: function () {
                let url = this.base;
                for (let i in this.options) {
                    url += '&' + i + '=' + this.options[i];
                }
                url += '&size=' + this.width + '*' + this.height;

                return url;
            },
            makePreviewer: function () {
                this.previewUrl = this.getUrl();
                this.preview = true;
            },
            createChart: function () {
                let that = this;
                $.post("{:U('Index/doCreate')}", this.options, function (res) {
                    if (res.status) {
                        layer.msg('图表创建成功!');
                        that.url = "{:U('Api/getChart')}&token=" + res.data.token;
                    } else {
                        alert('图表创建失败!')
                    }
                }, 'json');
            }
        },
        watch: {
            "options.table": function () {
                let that = this;
                let data = {
                    table: this.options.table
                };
                $.get("{:U('Index/getTableFields')}", data, function (res) {
                    if (res.status) {
                        that.fields = res.data;
                    }
                }, 'json');
            },
            'other_table':function(){
                this.options.table = "";
            }
        }
    })
</script>

</body>
</html>