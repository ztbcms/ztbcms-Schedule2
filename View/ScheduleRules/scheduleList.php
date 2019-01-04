<include file="Public/min-header"/>
<div class="wrapper">
    <include file="Public/breadcrumb"/>
    <style>
        #search-form > .form-group {
            margin-left: 10px;
        }
        
    </style>
    <!-- Main content -->
    <section class="content" id="app" v-cloak>
        <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">默认规则列表</h3>
                </div>
                <div class="panel-body">
                    <div class="navbar navbar-default">
                        <form action="" id="search-form2" class="navbar-form form-inline" method="post"
                              onsubmit="return false">
                            <input type="hidden" name="page" v-model="page">
                            <input type="hidden" name="store_count" value="{$_GET['store_count']}">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="name" value="" placeholder="模板名称" id="input-order-id"
                                           class="form-control name">
                                </div>
                            </div>

                            <!--排序规则-->
                            <input type="hidden" name="orderby" value="id desc"/>
                            <a  href="{:U('scheduleDetails')}" class="btn btn-info" title="新添模板">新添模板</a>

                            <button type="submit" @click="getList"
                                    id="button-filter search-order" class="btn btn-primary"><i class="fa fa-search"></i>
                                筛选
                            </button>

                        </form>
                    </div>
                    <div id="ajax_return">
                        <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-right">
                                            <a href="javascript:" @click="sort('goods_id')">ID</a>
                                        </td>
                                        <td class="text-left">
                                            <a href="javascript:" @click="sort('goods_name')">模板名称</a>
                                        </td>
                                        <td class="text-left">
                                            <a href="javascript:" @click="sort('cargo')">创建时间</a>
                                        </td>
                                        <td class="text-left">操作</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in lists">
                                        <td class="text-right">
                                            <p>{{ item.id }}</p>
                                        </td>
                                        <td class="text-left">
                                            <p>{{ item.name }}</p>
                                        </td>
                                        <td class="text-left">
                                            <p>{{ item.add_time_name }}</p>
                                        </td>
                                        <td class="text-left" style="width: 250px;">
                                            <div style="margin-bottom: 5px;">
                                                <a  :href="'{:U('scheduleDetails')}&id='+item.id" class="btn btn-primary" title="编辑"><i class="fa fa-pencil"></i></a>
                                                <p @click="delAcquiescent(item.id)" style="margin:10px 0px 10px 15px;" type="button"
                                                   class="btn btn-danger">刪除
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <!--     分页-->
                        <v-page :page="page" @update="getList" :page_count="page_count"></v-page>
                        <!--   /分页-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<include file="Public/vue"/>
<script>
    $(document).ready(function () {
        new Vue({
            el: '#app',
            data: {
                page: 1,
                total: 0,
                page_count: 0,
                order: 'desc',
                lists: [],
            },
            mixins: [window.__baseMethods],
            methods: {
                delAcquiescent: function (id) {
                    var that = this
                    layer.confirm('确定要删除吗', {}, function () {
                        $.ajax({
                            url: window.config.url.delScheduleAcquiescent,
                            data: {id: id},
                            dataType: 'json',
                            success: function (res) {
                                if (res.status) {
                                    that.getList()
                                    layer.closeAll()
                                } else {
                                    layer.msg(res.msg, {
                                        icon: 2,
                                        time: 1000
                                    });
                                }
                            }
                        });
                    })
                },
                getList: function () {
                    var that = this
                    $.ajax({
                        type: "POST",
                        url: window.config.url.scheduleList,
                        data: $('#search-form2').serialize(), // 你的formid
                        dataType: 'json',
                        success: function (res) {
                            if (res.status) {
                                console.log(res)
                                that.lists = res.data.items;
                                that.page = res.data.page;
                                that.total = res.data.total_items;
                                that.page_count = res.data.total_pages;
                            }
                        }
                    });
                }
            },
            mounted: function () {
                this.getList();
            },
            components: {
                'v-page': pageComponent
            }
        })

    });
</script>
</body>
</html>