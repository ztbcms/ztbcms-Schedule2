<include file="Public/min-header"/>
<include file="Public/vue"/>
<include file="Common/iconfont"/>
<link href="{$config_siteurl}statics/extres/shop/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet"
      type="text/css"/>
<script src="{$config_siteurl}statics/extres/shop/plugins/daterangepicker/moment.min.js"
        type="text/javascript"></script>
<script src="{$config_siteurl}statics/extres/shop/plugins/daterangepicker/daterangepicker.js"
        type="text/javascript"></script>

<style>

    .form-control{
        width: 100px;
    }

    .annotation {
        font-weight: bold;
        margin-left: 10px;
    }
</style>

<div id="app" class="wrapper" v-cloak>
    <include file="Public/breadcrumb"/>
    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right" style="height: 39px;">
                <a style="height: 40px;" href="{:U('scheduleList')}" data-toggle="tooltip" title=""
                   class="btn btn-default"
                   data-original-title="返回">返回</a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">默认规则管理</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">通用信息</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsForm">

                        <div class="tab-content">
                            <!--通用信息-->
                            <div class="tab-pane active" id="tab_tongyong">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr style="display: none;">
                                        <td>Store_ID</td>
                                        <td>
                                            <input type="text" name="store_id" class="form-control" value="{$store_id}"
                                                   style="width:200px;"/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>模板的名称</td>
                                        <td>
                                            <input  v-model="detail.name" type="text" name="name" class="form-control"
                                                   style="width:200px;"/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>模板关联的类型</td>
                                        <td>
                                            <input  v-model="detail.target_type" type="text" name="target_type" class="form-control"
                                                   style="width:200px;"/>
                                            <span class="annotation"> 如：goods_id </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>模板关联的类型的id</td>
                                        <td>
                                            <input  v-model="detail.target" type="text" name="target" class="form-control"
                                                   style="width:200px;"/>
                                            <span class="annotation"> 如：25 </span>
                                        </td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td>設置开始日期和截止日期:</td>
                                        <td>
                                            <input type="text" name="timegap" value="{$timePeriod}" placeholder="请选择日期"
                                                   id="timePeriod" class="form-control"
                                                   style="width: 200px;">
                                        </td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td>排序:</td>
                                        <td>
                                            <input type="number" name="timegap" value="" placeholder="排序"
                                                    class="form-control sort"
                                                   style="width: 200px;">
                                            <span class="annotation"> 排序越大越先檢驗 </span>
                                        </td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td>选择具体的时间:</td>
                                        <td>
                                            <select id="J_time_select" name="loop_type" class="form-control loop_type">
                                                <option value="month">每月</option>
                                                <option value="week">每周</option>
                                                <option value="daily">每天</option>
                                            </select>
                                            <span class="J_time_item" id="J_time_month"  style="">
                                            <select class="select_2 form-control month_day" name="month_day">
                                              <option value="1">1日</option>
                                              <option value="2">2日</option>
                                              <option value="3">3日</option>
                                              <option value="4">4日</option>
                                              <option value="5">5日</option>
                                              <option value="6">6日</option>
                                              <option value="7">7日</option>
                                              <option value="8">8日</option>
                                              <option value="9">9日</option>
                                              <option value="10">10日</option>
                                              <option value="11">11日</option>
                                              <option value="12">12日</option>
                                              <option value="13">13日</option>
                                              <option value="14">14日</option>
                                              <option value="15">15日</option>
                                              <option value="16">16日</option>
                                              <option value="17">17日</option>
                                              <option value="18">18日</option>
                                              <option value="19">19日</option>
                                              <option value="20">20日</option>
                                              <option value="21">21日</option>
                                              <option value="22">22日</option>
                                              <option value="23">23日</option>
                                              <option value="24">24日</option>
                                              <option value="25">25日</option>
                                              <option value="26">26日</option>
                                              <option value="27">27日</option>
                                              <option value="28">28日</option>
                                              <option value="29">29日</option>
                                              <option value="30">30日</option>
                                              <option value="31">31日</option>
                                              <option value="99">最后一天</option>
                                            </select>
                                            </span>
                                            <span class="J_time_item" id="J_time_week" style="display:none;">
                                            <select class="select_2 mr10 form-control week_day" name="week_day">
                                              <option value="1">周一</option>
                                              <option value="2">周二</option>
                                              <option value="3">周三</option>
                                              <option value="4">周四</option>
                                              <option value="5">周五</option>
                                              <option value="6">周六</option>
                                              <option value="7">周日</option>
                                            </select>
                                            </span>
                                            
                                            <span class="J_time_item" id="J_time_daily"  style=""></span>
                                            <p style="margin-top:-6px;" class="btn btn-info" @click="getAddRules()" title="" data-original-title="添加">添加</p>
                                        </td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td><p class="annotation">demo的使用:</p></td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td>选择时间进行校验是否存在:</td>
                                        <td>
                                            <input type="text"
                                                   name="timegap" value="{$timePeriod}"
                                                   placeholder="请选择日期"
                                                   id="checkTime" class="form-control"
                                                   style="width: 200px;">

                                            <p style="margin-top:-6px;" class="btn btn-danger" @click="getCheckTime()" title="" data-original-title="校验">校验</p>
                                        </td>
                                    </tr>

                                    <tr v-if="id > 0">
                                        <td>查询时间段内存在的预约:</td>
                                        <td>
                                            <input type="text"
                                                   name="timegap" value=""
                                                   placeholder="请选择日期"
                                                   id="timescale" class="form-control"
                                                   style="width: 200px;">
                                            <p style="margin-top:-6px;" class="btn btn-danger" @click="getQueryRule()" title="" data-original-title="查询">查询</p>
                                        </td>
                                    </tr>
                                    
                                    </tbody>
                                </table>
                            </div>


                            <div v-if="id > 0" id="goods_spec_table2">
                                <table class="table table-bordered" id="spec_input_tab">
                                    <tbody>
                                    <tr>
                                        <td><b>开始时间 - 结束时间</b></td>
                                        <td><b>预约类型</b></td>
                                        <td><b>排序</b></td>
                                        <td><b>操作</b></td>
                                    </tr>
                                    <tr v-for="(items,k) in rule_list">
                                        <td>{{ items.time_name }}</td>
                                        <td>{{ items.loop_type_name }}</td>
                                        <td>{{ items.sort }}</td>
                                        <td>
                                            <a style="margin-bottom: 5px;" class="btn btn-danger"
                                               @click="delRule(items.id)">
                                                <i class="iconfont icon-close"></i>删除
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <!--表单数据-->
                    <div class="pull-right">
                        <button class="btn btn-primary" @click="saveBtn" title="" data-toggle="tooltip"
                                type="button"
                                data-original-title="保存">保存
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </section>
</div>
<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                id:"{$_GET['id']}",
                detail:{
                    name:'',
                    target_type:'',
                    target:''
                },
                rule_list:[]
            },
            mixins: [window.__baseMethods],
            methods: {
                getList: function () {
                    var that = this;
                    if (that.id) {
                        $.ajax({
                            url: window.config.url.scheduleDetails,
                            type: "post",
                            data: {
                                id: that.id
                            },
                            dataType: 'json',
                            success: function (res) {
                                if (res.status) {
                                    that.detail.name = res.data.name;
                                    that.detail.target_type = res.data.target_type;
                                    that.detail.target = res.data.target;
                                    that.rule_list = res.data.rule;
                                }
                            }
                        })
                    }
                },
                getAddRules:function () {
                    var that = this;
                    var data = {
                        detail:that.detail,
                        id:that.id,
                        timePeriod:$("#timePeriod").val(),
                        loop_type:$(".loop_type").val(),
                        month_day:$(".month_day").val(),
                        week_day:$(".week_day").val(),
                        now_time:$(".now_time").val(),
                        sort:$('.sort').val()
                    };
                    $.ajax({
                        url: window.config.url.rulesAddEdit,
                        type: "post",
                        data: data,
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                that.getList();
                            } else {
                                layer.alert(res.msg);
                            }
                        }
                    })
                },
                delRule:function (id) {
                    var that = this;
                    $.ajax({
                        url: window.config.url.rulesDel,
                        type: "post",
                        data: {
                            id:id
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                that.getList();
                            }
                        }
                    })
                },
                getCheckTime:function () {
                    var that = this;
                    $.ajax({
                        url: window.config.url.checkTime,
                        type: "post",
                        data: {
                            checkTime:$('#checkTime').val(),
                            id:that.id
                        },
                        dataType: 'json',
                        success: function (res) {
                            layer.alert(res.msg);
                        }
                    })
                },
                getQueryRule:function () {
                    var that = this;
                    $.ajax({
                        url: window.config.url.queryRule,
                        type: "post",
                        data: {
                            checkTime:$('#timescale').val(),
                            id:that.id
                        },
                        dataType: 'json',
                        success: function (res) {
                            layer.alert(res.msg);
                        }
                    })
                },
                saveBtn:function () {
                    var that = this;
                    $.ajax({
                        url: window.config.url.scheduAddEdit,
                        type: "post",
                        data: {
                            detail:that.detail,
                            id:that.id
                        },
                        dataType: 'json',
                        success: function (res) {
                            if(that.id){
                                layer.alert(res.msg);
                                that.getList();
                            } else {
                                layer.alert(res.msg, {
                                    skin: 'layui-layer-molv'
                                    ,closeBtn: 0
                                }, function(){
                                    window.location.href = "/Schedule/ScheduleRules/scheduleDetails?id="+res.data.schedule_id;
                                });
                            }
                        }
                    })
                }
            },
            computed: {
                
            },
            mounted: function () {
              this.getList();
            }
        });
        $('#timePeriod').daterangepicker({
            format: "YYYY/MM/DD",
            singleDatePicker: false,
            showDropdowns: true,
            minDate: new Date().getDate(),
            maxDate: '2030/01/01',
            startDate: new Date().getDate(),
            locale: {
                applyLabel: '确定',
                cancelLabel: '取消',
                fromLabel: '起始时间',
                toLabel: '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                firstDay: 1
            }
        });

        $('#checkTime').daterangepicker({
            format: "YYYY/MM/DD",
            singleDatePicker: true,
            showDropdowns: true,
            minDate: new Date().getDate(),
            maxDate: '2030/01/01',
            startDate: new Date().getDate(),
            locale: {
                applyLabel: '确定',
                cancelLabel: '取消',
                fromLabel: '起始时间',
                toLabel: '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                firstDay: 1
            }
        });

        $('#timescale').daterangepicker({
            format: "YYYY/MM/DD",
            singleDatePicker: false,
            showDropdowns: true,
            minDate: new Date().getDate(),
            maxDate: '2030/01/01',
            startDate: new Date().getDate(),
            locale: {
                applyLabel: '确定',
                cancelLabel: '取消',
                fromLabel: '起始时间',
                toLabel: '结束时间',
                customRangeLabel: '自定义',
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                firstDay: 1
            }
        });
    });

    $(function(){
        $('#J_time_select').on('change', function(){
            $('#J_time_'+ $(this).val()).show().siblings('.J_time_item').hide();
        });
        $("#J_type_select").on('change', function(){
            if($(this).val() == "0"){
                $('.J_type_item').hide();
            }else{
                $('#type'+ $(this).val()).show().siblings('.J_type_item').hide();
            }
        });
    });
</script>
</body>

</html>