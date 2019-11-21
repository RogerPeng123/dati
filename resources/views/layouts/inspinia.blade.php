<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="{{asset(getThemeAssets('bootstrap/css/bootstrap.min.css', true))}}" rel="stylesheet">
    <link href="{{asset(getThemeAssets('font-awesome/css/font-awesome.min.css', true))}}" rel="stylesheet">
    <link href="{{asset(getThemeAssets('animate/animate.css', true))}}" rel="stylesheet">
    @yield('css')
    <link href="{{asset(getThemeAssets('css/style.css'))}}" rel="stylesheet">
    <style type="text/css">
        .table th, .table td {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
</head>
<body class="">
<div id="wrapper">
    @include('layouts.partials.'.getTheme().'-sidebar')

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">
                            Hi,<span id="admin-model" style="cursor:pointer;">{{auth()->user()->name}}</span>
                        </span>
                    </li>

                    <li>
                        <a id="logout-a">
                            <i class="fa fa-sign-out"></i>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        @yield('content')
        <div class="footer">
            <div class="pull-right">
                <i class="fa fa-github"></i>
                <strong>
                    <a href="https://github.com/FlyingOranges/any" target="_blank">
                        https://github.com/FlyingOranges/any
                    </a>
                </strong>
            </div>
            <div>
                <strong>Copyright</strong> 通用后台 &copy; http://www.xxx.com
            </div>
        </div>

    </div>
</div>
<div class="modal inmodal" id="adminModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">管理员信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">昵称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control admin-name" value="{{ auth()->user()->name }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">账号</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ auth()->user()->username }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control admin-password" placeholder="不填表示不更新">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="save-admin" class="btn btn-primary">更新</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script src="{{asset(getThemeAssets('jquery/jquery-2.1.1.js', true))}}"></script>
<script src="{{asset(getThemeAssets('bootstrap/js/bootstrap.min.js', true))}}"></script>
<script src="{{asset(getThemeAssets('metisMenu/jquery.metisMenu.js', true))}}"></script>
<script src="{{asset(getThemeAssets('slimscroll/jquery.slimscroll.min.js', true))}}"></script>
<script src="{{asset(getThemeAssets('js/inspinia.js'))}}"></script>
@yield('js')

<script>
    // flash提示框 3秒后自动消失
    $("[role='alert']").delay(3000).hide(0);

    $('#logout-a').on('click', function () {
        $('#logout-form').submit();
    });

    $('#admin-model').on('click', function () {
        $('#adminModel').modal('show');
    });

    $('#save-admin').on('click', function () {
        var name = $('.admin-name').val();
        var password = $('.admin-password').val();

        var url = "{{ route('admin.setting.adminer') }}";
        var data = {name: name, password: password};

        $.ajax({
            url: url, data: data, type: "post", dataType: 'json',
            headers: {'x-csrf-token': $('meta[name="csrf-token"]').attr('content')},
            success: function (e) {
                if (e.status == 200) {
                    layer.alert('更新成功');
                    return false;
                }
                if (e.status == 401) {
                    layer.alert('更新失败');
                    return false;
                }
            }
        });

    });

</script>
</body>
</html>