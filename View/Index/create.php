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
                <div class="col-md-3">
                    <input class="form-control" type="text" name="title" v-model="options.title" id="title"
                           placeholder="请输入图表名称">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="">数据来源表</label>
            <div class="row">
                <div class="col-md-3">
                    <input class="form-control" type="text" v-model="options.table" v-if="other_table"
                           placeholder="请填写表名，不带前缀" @blur="getTableFields">
                    <select class="form-control" name="table" id="table" v-model="options.table" v-else
                            @change="getTableFields">
                        <option value="">选择模型表</option>
                        <volist name="tables" id="table">
                            <option value="{$table['tablename']}">{$table[name]}</option>
                        </volist>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="form-label" for="other_table" style="margin-top: 5px;">
                        <input type="checkbox" v-model="other_table" id="other_table">没有列出我需要的数据表，我需要手工填写
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">分组依据（X 轴）</label>

            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" name="x" id="x_field" v-model="options.x">
                        <option value="">请选择字段</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="x_type" id="x_type" v-model="options.x_type">
                        <option value="">选择分组方式</option>
                        <volist name="xType" id="item">
                            <option value="{$key}">{$item}</option>
                        </volist>
                    </select>
                </div>


                <div class="col-md-3" v-if="options.x_type.toUpperCase() == '__SCRIPT'">
                    <select class="form-control" name="x" id="x_script" v-model="options.x">
                        <option value="">请选择脚本</option>
                        <volist name="xScript" id="item">
                            <option value="{$item}">{$item}</option>
                        </volist>
                    </select>
                </div>

                <div v-if="options.x_type.toUpperCase() == '__TIME'">
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="x" id="x_time" v-model="x_space">
                    </div>
                    <div class="col-md-2">
                        <select name="time_unit" id="time_unit" v-model="time_unit" class="form-control">
                            <option value="I">分钟</option>
                            <option value="H">小时</option>
                            <option value="D">天</option>
                            <option value="M">月</option>
                            <option value="Y">年</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" v-if="options.x_type.toUpperCase() == '__FOREIGN'">
                <label class="form-label" for="">关联表</label>
                <div class="row">
                    <div class="col-md-3">
                        <input class="form-control" type="text" v-model="options.x_foreign_table"
                               v-if="other_foreign_table" placeholder="请填写表名，不带前缀" @blur="getForeignTableFields">
                        <select class="form-control" name="table" id="table" v-model="options.x_foreign_table" v-else
                                @change="getForeignTableFields">
                            <option value="">选择模型表</option>
                            <volist name="tables" id="table">
                                <option value="{$table['tablename']}">{$table[name]}</option>
                            </volist>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-control" name="table" id="table" v-model="options.x_foreign_key">
                            <option value="">请选择关联字段</option>
                            <option v-for="field in foreign_fields" :value="field">{{ field }}</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-control" name="table" id="table" v-model="options.x_foreign_field">
                            <option value="">请选择显示字段</option>
                            <option v-for="field in foreign_fields" :value="field">{{ field }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label" for="other_foreign_table" style="margin-top: 5px;">
                            <input type="checkbox" v-model="other_foreign_table" id="other_foreign_table">没有列出我需要的数据表，我需要手工填写
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">统计方式（Y 轴）</label>
            <div class="row">

                <div class="col-md-3" v-if="options.y_type.toUpperCase() != '__SCRIPT'">
                    <select class="form-control" name="y" id="y_field" v-model="options.y">
                        <option value="">请选择字段</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="y_type" id="y_type" v-model="options.y_type">
                        <option value="">选择统计方式</option>
                        <volist name="yType" id="item">
                            <option value="{$key}">{$item}</option>
                        </volist>
                    </select>
                </div>

                <div class="col-md-3" v-if="options.y_type.toUpperCase() == '__SCRIPT'">
                    <select class="form-control" name="y" id="y_script" v-model="options.y">
                        <option value="">请选择脚本</option>
                        <volist name="yScript" id="item">
                            <option value="{$item}">{$item}</option>
                        </volist>
                    </select>
                </div>
            </div>

        </div>

        <div class="form-group" v-if="options.y_type.toUpperCase() != '__SCRIPT'">
            <label class="form-label" for="">额外筛选条件</label>
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control" name="filter_field" id="filter_field" v-model="filter_field">
                        <option value="">请选择统计方式</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-control" name="filter_operator" id="filter_operator"
                            v-model="filter_operator">
                        <option value="">请选择筛选方式</option>
                        <option value="EQ">等于</option>
                        <option value="NEQ">不等于</option>
                        <option value="GT">大于</option>
                        <option value="GET">大于等于</option>
                        <option value="LT">小于</option>
                        <option value="LET">小于等于</option>
                        <option value="BETWEEN">介于</option>
                        <option value="LIKE">模糊查询</option>
                        <option value="IS NULL">为 null</option>
                        <option value="IS NOT NULL">不为 null</option>
                    </select>
                </div>

                <div class="col-md-2" v-if="!/NULL/.test(filter_operator)">
                    <input class="form-control" type="text" name="filter_value" id="filter_value"
                           v-model="filter_value"
                           :placeholder="filter_operator.toUpperCase() == 'BETWEEN'?'以 , 分隔的两个筛选值':'请输入筛选值'"/>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info" type="button" @click="addFilter">添加</button>
                </div>
            </div>

            <div class="row" v-if="Object.keys(filter.field).length >= 1">
                <br>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>字段</th>
                            <th>筛选条件</th>
                            <th>筛选值</th>
                            <th>操作</th>
                        </tr>
                        <tr v-for="(field,index) in filter.field" :index="index">
                            <td>{{ index }}</td>
                            <td>{{ filter.operator[index] }}</td>
                            <td>{{ filter.value[index] }}</td>
                            <td>
                                <button class="btn btn-warning" type="button" @click="delFilter(index)">删除</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        <div class="form-group" v-if="options.y_type.toUpperCase() != '__SCRIPT'">
            <label class="form-label" for="">统计时间范围</label>
            <div class="row">

                <div class="col-md-3">
                    <select class="form-control" name="time_field" id="time_field" v-model="options.time_field">
                        <option value="">请选择时间字段</option>
                        <option v-for="field in fields" :value="field">{{ field }}</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-control" name="time_section" id="time_section" v-model="options.time_section">
                        <option value="">请选择时间范围</option>
                        <volist name="during" id="item">
                            <option value="{$key}">{$item}</option>
                        </volist>
                    </select>
                </div>

            </div>
        </div>

        <div class="form-group" v-if="options.y_type.toUpperCase() != '__SCRIPT'">
            <label class="form-label" for="">是否显示结果为0的列</label>
            <div class="row">
                <div class="col-md-2">
                    <select class="form-control" name="show_all" id="show_all" v-model="options.show_all">
                        <option value="1">是</option>
                        <option value="0">否</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">鼠标悬浮提示</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="tips" id="tips" v-model="options.tips"
                           placeholder="请输入图表悬浮提示">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">排序方式</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="order" id="order" v-model="options.order"
                           placeholder="请输入排序方式">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="">是否使用缓存（单位：分钟，0表示不缓存）</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="cache" id="cache" v-model="options.cache"
                           placeholder="请输入缓存时间">
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
            other_table: false,
            foreign_fields: [],
            other_foreign_table: false,
            width: '900',
            height: '400',
            filter_field: '',
            filter_operator: '',
            filter_value: '',
            x_space: '',
            time_unit: 'I',
            filter: {
                field: {},
                operator: {},
                value: {},
            },
            options: {
                title: '',
                table: '',
                time_field: '',
                time_section: '',
                x: '',
                x_type: '',
                x_foreign_table: '',
                x_foreign_key: '',
                x_foreign_field: '',
                y: '',
                y_type: '',
                tips: '',
                order: 'id',
                show_all: '1',
                cache: 0,
            },
            url: ''
        },
        methods: {
            addFilter: function () {
                //赋值
                this.filter.field[this.filter_field] = this.filter_field;
                this.filter.operator[this.filter_field] = this.filter_operator;
                this.filter.value[this.filter_field] = this.filter_value;

                //重置选项
                this.filter_field = "";
                this.filter_operator = "";
                this.filter_value = "";

            },
            delFilter: function (index) {
                //删除对应值
                delete this.filter.field[index];
                delete this.filter.operator[index];
                delete this.filter.value[index];

                //复制对象
                var field = this.filter.field;
                var operator = this.filter.operator;
                var value = this.filter.value;

                //重置vue对象值
                this.filter.field = null;
                this.filter.operator = null;
                this.filter.value = null;

                //重新赋值
                this.filter.field = field;
                this.filter.operator = operator;
                this.filter.value = value;
            },
            getTableFields: function () {
                var that = this;
                var data = {
                    table: this.options.table
                };
                $.get("{:U('Index/getTableFields')}", data, function (res) {
                    if (res.status) {
                        that.fields = res.data;
                    }
                }, 'json');
            },
            getForeignTableFields: function () {
                var that = this;
                var data = {
                    table: this.options.x_foreign_table
                };
                $.get("{:U('Index/getTableFields')}", data, function (res) {
                    if (res.status) {
                        that.foreign_fields = res.data;
                    }
                }, 'json');
            },
            getUrl: function () {
                var url = this.base;

                if (this.options.x_type.toUpperCase() === "__TIME") {
                    this.options.x = this.time_unit + '-' + this.x_space;
                }

                //获取配置
                for (var i in this.options) {
                    url += '&' + i + '=' + this.options[i];
                }
                //获取大小设置
                url += '&size=' + this.width + '*' + this.height;

                //获取筛选条件
                var where = '';
                for (var i in this.filter.field) {
                    where += '&filter[' + i + ']=' + this.filter.field[i];
                    where += '&operator[' + i + ']=' + this.filter.operator[i];
                    where += '&value[' + i + ']=' + this.filter.value[i];
                }
                url += where;

                return url;
            },
            makePreviewer: function () {
                this.previewUrl = this.getUrl();
                this.preview = true;
            },
            createChart: function () {
                var that = this;

                if (this.options.x_type.toUpperCase() === "__TIME") {
                    this.options.x = this.time_unit + '-' + this.x_space;
                }

                var postData = Object.assign(this.options, this.filter);

                $.post("{:U('Index/doCreate')}", postData, function (res) {
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
            'other_table': function () {
                this.options.table = "";
            }
        }
    })
</script>

</body>
</html>