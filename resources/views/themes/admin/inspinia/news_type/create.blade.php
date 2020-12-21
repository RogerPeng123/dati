@extends('layouts.'.getTheme())
@section('css')
    <link href="{{asset(getThemeAssets('iCheck/custom.css', true))}}" rel="stylesheet">
@endsection
@section('content')
    @inject('userPresenter','App\Repositories\Presenters\Admin\UserPresenter')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>新闻中心</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:void(0);">新闻中心</a>
                </li>
                <li class="active">
                    <strong>新闻分类</strong>
                </li>
            </ol>
        </div>

        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{ route('learn.index') }}">
                    <i class="fa fa-reply"></i>
                    {!! trans('common.cancel') !!}
                </a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{!! trans('common.create') !!} 新闻分类</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @include('flash::message')
                        <form method="post" action="{{ route('news-type.store') }}" class="form-horizontal">
                            {{csrf_field()}}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title">
                                    @if ($errors->has('title'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>


                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="">{!!trans('common.cancel')!!}</a>
                                    @if(haspermission('newstypecontroller.store'))
                                        <button class="btn btn-primary" id="questionSub"
                                                type="submit">{!! trans('common.create') !!}</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--@include(getThemeView('user.modal'))--}}
@endsection
@section('js')
    <script type="text/javascript" src="{{asset(getThemeAssets('iCheck/icheck.min.js', true))}}"></script>
    <script type="text/javascript" src="{{asset(getThemeAssets('js/check.js'))}}"></script>

    <!-- 注意， 只需要引用 JS，无需引用任何 CSS ！！！-->
    <script type="text/javascript" src="https://unpkg.com/wangeditor@3.1.1/release/wangEditor.min.js"></script>
    <script type="text/javascript">
        var E = window.wangEditor;
        var editor = new E('#editor');
        // 或者 var editor = new E( document.getElementById('editor') )


        editor.customConfig.showLinkImg = false; // 隐藏“网络图片”tab
        // editor.customConfig.uploadImgServer = '/upload';  // 上传图片到服务器
        editor.customConfig.uploadImgShowBase64 = true;   // 使用 base64 保存图片
        editor.create();

        $('#questionSub').on('click', function () {
            $('#content').val(editor.txt.html());
        });
    </script>

    <script>
        $(document).ready(function () {

            $(".iradio_square-green").on('click', function () {
                $('.iradio_square-green').each(function (key, val) {
                    if ($(val).hasClass('checked')) {
                        $(val).removeClass('checked');
                    }
                });
            });


            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
@endsection