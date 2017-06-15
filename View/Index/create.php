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

    <script src="https://cdn.bootcss.com/vue/2.3.3/vue.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>

<div id="app" class="container">
    <div class="row">
        <form>
            <div class="from-group">
                <label class="from-label" for="">数据来源表</label>
                <input class="from-control" type="text" name="title" v-model="options.title" id="title" placeholder="请输入图表名称">
            </div>
            <div class="from-group">
                <label class="from-label" for="">数据来源表</label>
                <select class="from-control" name="table" id="table" v-model="options.table">
                    <option value="">选择模型表</option>
                    <volist name="tables" id="table">
                        <option value="{$table['tablename']}">{$table[name]}</option>
                    </volist>
                </select>
            </div>

            <div class="from-group">
                <label class="from-label" for="">请选择统计基准（X 轴）</label>

                <select class="from-control" name="x_type" id="x_type" v-model="options.x_type">
                    <option value="">选择统计方式</option>
                    <option value="field">字段</option>
                    <option value="__during">时间段</option>
                    <option value="__script">脚本</option>
                </select>

                <select class="from-control" name="x" id="x_field" v-model="options.x" v-if="options.x_type == 'field'">
                    <option value="">请选择字段</option>
                    <option v-for="field in fields" :value="field">{{ field }}</option>
                </select>

                <select class="from-control" name="x" id="x_script" v-model="options.x" v-if="options.x_type == '__script'">
                    <option value="">请选择脚本</option>
                    <option v-for="field in fields" :value="field">{{ field }}</option>
                </select>

            </div>

            <div class="from-group">
                <label class="from-label" for="">统计数（Y 轴）</label>
                <select class="from-control" name="y_type" id="y_type" v-model="options.y_type">
                    <option value="">选择统计方式</option>
                    <option value="count">字段（计数总数）</option>
                    <option value="__script">脚本</option>
                </select>

                <select class="from-control" name="y" id="y_field" v-model="options.y" v-if="options.y_type == 'count'">
                    <option value="">请选择字段</option>
                    <option v-for="field in fields" :value="field">{{ field }}</option>
                </select>

                <select class="from-control" name="y" id="y_script" v-model="options.y" v-if="options.y_type == '__script'">
                    <option value="">请选择脚本</option>
                    <option v-for="field in fields" :value="field">{{ field }}</option>
                </select>

            </div>

            <div class="from-group">
                <label class="from-label" for="">统计数（Y 轴）</label>
                <input type="text" class="from-control" name="tips" id="tips" v-model="options.tips" placeholder="请输入图表悬浮提示">
            </div>


        </form>

        <button type="button" @click="makePreviewer">生成预览</button>


        <br><br>
        <button type="button" @click="createChart">生成图表</button>
        <div id="url" v-if="url != ''">
            生成成功！永久链接为：{{ url }}
        </div>
    </div>

    <div v-if="preview">
        演示：
        <iframe :src="previewUrl" frameborder="0" :width="width" :height="height"></iframe>
    </div>
</div>

<script>
    new Vue({
        el:'#app',
        data:{
            base:"{:U('Chart/Index/previewer')}",
            preview: true,
            previewUrl:'http://ztbcms.de/index.php?g=Chart&m=Api&a=getChart&token=a9a41bec8365599185c729e2047ae114&type=1&size=900*400',
            fields:[],
            width:'900',
            height:'400',
            options:{
                title:'',
                table:'',
                x:'',
                x_type:'',
                y:'',
                y_type:'',
                tips:''
            },
            url:''
        },
        methods:{
            getUrl :function () {
                let url = this.base;
                for(let i in this.options){
                    url += '&' + i + '=' + this.options[i];
                }
                url += '&size=' + this.width + '*' + this.height;

                return url;
            },
            makePreviewer:function () {
                this.previewUrl = this.getUrl();
            },
            createChart:function(){
                let that = this;
                $.post("{:U('Index/doCreate')}",this.options,function(res){
                    if(res.status){
                        alert('图表创建成功!');
                        that.url = "{:U('Api/getChart')}&token=" + res.data.token;            
                    }else{
                        alert('图表创建失败!')
                    }
                },'json');
            }
        },
        watch:{
            "options.table":function(){
                let that = this;
                let data = {
                    table:this.options.table
                };
                $.get("{:U('Index/getTableFields')}",data,function(res){
                    if(res.status){
                        that.fields = res.data;
                    }
                },'json');
            }
        }
    })
</script>

</body>
</html>