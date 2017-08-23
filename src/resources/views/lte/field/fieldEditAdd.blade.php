@extends('umi::layouts.master')

@section('content')

    <?php $assetPath = url(config('umi.assets_path')) ?>
    <?php $path = url($assetPath . '/lte') ?>

    <link rel="stylesheet" href="{{$assetPath}}/labelauty/jquery-labelauty.css" />

    @include('umi::common.fieldDisplay.headButtons')

    <div class="col-sm-12">
        <div class="box box-{{$type==='edit'?'warning':'danger'}} box-solid">
            <div class="box-header with-border">
                <label class="box-title">
                    {{$type==='edit'?trans('umiTrans::field.edit'):trans('umiTrans::field.add')}}
                </label>
            </div>
            <div class="box-body">
                <form class="form-horizontal" id="validation-form" method="post" action="{{url('fieldDisplay')}}/{{$table}}/addType/{{$type}}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="control-label col-sm-1" for="table_id">{{trans('umiTrans::field.selectTable')}}</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="tableName" name="table_id" required title="Please select a table">
                                <option value=""></option>
                                @foreach($tableList as $item)
                                    <option value="{{$item->id}}">{{$item->table_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn bg-maroon btn-flat" id="quickAdd"
                                    data-rel="tooltip" data-placement="bottom" title="Fill up all missing fields">
                                {{trans('umiTrans::field.quickAdd')}}
                                <i class="fa fa-bolt"></i>
                            </button>
                            <button type="button" class="btn bg-black btn-flat" id="hideQuickAdd">
                                {{trans('umiTrans::field.hideField')}}
                                <i class="fa fa-eye-slash"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-flat" id="showQuickAdd">
                                {{trans('umiTrans::field.showField')}}
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="list-seperator"></div>

                    <div class="col-sm-12">
                        <div id="fieldDisplay">
                        </div>
                    </div>
-
                    {{-- drop down box for selecting field --}}
                    @include('umi::common.fieldDisplay.fieldsDropDownBox')

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="type">{{trans('umiTrans::field.dataType')}}</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="type" id="type" required>
                                <option value=''>{{trans('umiTrans::field.selectType')}}</option>
                                @foreach($showInputInterface as $key => $value)
                                    <option value="{{$key}}"
                                            showInputInterface="{{$value['showInputInterface']}}"
                                    >{{$key}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="relation_display">{{trans('umiTrans::field.relationRule')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="relation_display" id="relation_display" placeholder="tableName:fieldName">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="custom_value">{{trans('umiTrans::field.customValue')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="custom_value" id="custom_value">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="display_name">{{trans('umiTrans::field.displayName')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="display_name" id="display_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="details">{{trans('umiTrans::field.detail')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="details" id="details">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="validation">{{trans('umiTrans::field.validation')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="validation" id="validation">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="order">{{trans('umiTrans::field.order')}}</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="order" id="order" number="true" value=0>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-1" for="is_showing">{{trans('umiTrans::field.isEditable')}}</label>
                        <div class="col-sm-1">
                            <input class="to-labelauty-icon" type="radio" name="is_editing" data-labelauty="{{trans('umiTrans::field.show')}}" checked value="1"/>
                        </div>
                        <div class="col-sm-1">
                            <input class="to-labelauty-icon" type="radio" name="is_editing" data-labelauty="{{trans('umiTrans::field.hide')}}" value="0"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <button class="btn btn-{{$type==='edit'?'warning':'danger'}} btn-flat" type="submit"><span class="bolder">{{trans('umiTrans::field.addField')}}</span></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="overlay" hidden>
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    <script src="{{$assetPath}}/js/jquery.validate.min.js"></script>
    <script src="{{$assetPath}}/labelauty/jquery-labelauty.js"></script>
    <script src="{{$assetPath}}/js/bread/umiTableBread.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            //初始化
            //init
            $("#type").val('');
            $("#relation_display").val('');

            //刷新当前页面是保持数据显示
            //keep data display when refresh page
            var tableId = $('#tableName').val();
            var url = "{{url('fieldDisplay')}}/{{$table}}/id/";
            if (tableId !== '') {
                //加载符号
                //showing loading icon
                $('#fieldDisplay').html("<i id='responseLoading' class='fa fa-spinner fa-spin fa-orange'></i>");
                $('.overlay').show();
                loadTable(url + tableId);
            }

            //选择table
            //select table
            $('#tableName').change(function () {

                $("#type").val('');
                $("#relation_display").val('');

                if ($(this).val() === '') {
                    return false;
                }

                //加载字段
                //loading fields
                $('#fieldDisplay').html("<i id='responseLoading' class='fa fa-spinner fa-spin fa-orange'></i>");
                $('.overlay').show();
                tableId = $(this).val();
                loadTable(url + tableId);
            });

            //选择数据类型
            //select data type
            $('#type').change(function () {

                //每次选择类型都要清空规则 以免发生错误
                //every time select data type, the rule must be empty just in case error occurs
                $('#relation_display').val('');
                $('#custom_value').val('');

                //badge类型比较特殊, 需要和badge数据表协同完成badge显示, 所以规则字段必须默认是 "表名:字段名"
                //badge type is special, need to complete the function working with badge table, so the rule must be: "table name: field name"
                if ($(this).val() === 'badge') {
                    var tableName = $("#tableName").find("option:selected").text();
                    var fieldName = $("#field").find("option:selected").text();

                    if (tableName === '' || fieldName === '') {
                        layer.alert('For badge: table name and field name can not be empty', '');
                        $("#type").val('');
                        return;
                    }

                    $('#relation_display').val(tableName + ":" + fieldName);
                    return;
                }

                //如果字段showInputInterface是真, 则需要一个规则,在弹出窗口定义规则
                //if showInputInterface is true, then need a rule that will be created in pop window
                var showInputInterface = $(this).find("option:selected").attr("showInputInterface");
                if(showInputInterface) {
                    var dataType = $(this).val();
                    var url = '{{url("relationRule/relation_display/custom_value")}}/' + dataType;
                    layer.open({
                        type: 2,
                        title: 'Creating a relation rule',
                        shadeClose: false,
                        area: ['1000px', '70%'],
                        content: url,
                        end: function () {
                            //如果没有生成一个规则, 则数据类型选项重置为空
                            //if no rule has been generated than data type will be reset to empty
                            if ($('#relation_display').val() === '' && $('#custom_value').val() === '')
                                $(this).val('');
                        }
                    });
                }
            });

            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({html:true});

            //快速添加字段
            //quick add
            $('#quickAdd').click(function () {
                $(this).blur();
                var tableId = $('#tableName').val();
                if (tableId === '') {
                    layer.alert('Please select a table', {title: 'Wrong'});
                    return;
                }
                layer.confirm('All the fields will be added by default setting<br> except the fields already exist',
                    {
                        btn: ['Add', 'Cancel'],
                        title: 'Sure?'
                    },
                    function () {
                        layer.closeAll();
                        var load = layer.load(3, {shade: [0.5, '#000']});
                        var existFields = $('#existFields').val();
                        var url = "{{url('fieldDisplay')}}/{{$table}}/quickAdd/" + existFields + "/" + tableId + "/" + '{{$type}}';

                        //确认执行快速添加字段
                        //confirm to quick add fields
                        $.ajax({
                            type: 'get',
                            url: url,
                            success: function (data) {
                                layer.close(load);
                                if (data === '0') {
                                    layer.alert('Nothing Added!');
                                } else {
                                    layer.alert(data + ' records had been added', function () {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function () {
                                layer.close(load);
                                layer.alert('Request failed, please try it again', {title: 'Wrong'});
                            }
                        });
                    },
                    function () {
                    }
                );
            });

            //隐藏 查询出的字段列表
            //hide all fields list from selecting table name
            $('#hideQuickAdd').click(function () {
                $('#fieldDisplay').slideUp();
            });

            //显示 查询出的字段列表
            //show all fields list from selecting table name
            $('#showQuickAdd').click(function () {
                $('#fieldDisplay').slideDown();
            });

            //验证
            //validation
            $('#validation-form').validate({
                errorClass: "fa-red"
            });

            //单选框
            //switch
            $(".to-labelauty-icon").labelauty({
                minimum_width: "120px",
                same_width: true
            });
        });

        //删除记录 (默认不带关联删除, 可以附带参数进行关联删除)
        //delete record (no related operation as default, can add parameter for relation operation
        function recordDelete(tableName, tableId) {
            var url = "{{url('deleting')}}/" + tableName + '/' + tableId;
            var delConfirm = layer.open ({
                type: 2,
                title: 'Deleting',
                maxmin: true,
                shadeClose: true,
                area : ['800px' , '520px'],
                content: url
            });
        }

        //加载数据当选择表名的时候
        //load data when select table name
        function loadTable(url) {
            $.ajax({
                type: 'get',
                url: url,
                success: function (data) {
                    $('#fieldDisplay').html(data).hide().slideDown(1000,function () {});

                },
                error: function () {
                    $('#fieldDisplay').html('loading error');
                },
                complete: function () {
                    $('.overlay').hide();
                }
            });
        }

        //显示删除确认页面
        //show the confirmation page before deleting
        function showDeleting(url){
            layer.open({
                type: 2,
                title: 'deleting',
                maxmin: true,
                shadeClose: true,
                area: ['800px', '520px'],
                content: url
            });
        }
    </script>
@endsection