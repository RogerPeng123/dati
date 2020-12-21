@extends('layouts.'.getTheme())
@section('css')
    <link href="{{asset(getThemeAssets('iCheck/custom.css', true))}}" rel="stylesheet">
    <style type="text/css">
        .iCheck-helper {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: rgb(255, 255, 255);
            border: 0;
            opacity: 0;
        }

        .radio-class {
            position: absolute;
            opacity: 0;
        }

        .i-checks {
            text-align: center;
            float: left;
            margin-left: 15px;
        }
    </style>
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
                    <strong>编辑新闻</strong>
                </li>
            </ol>
        </div>

        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{ route('news-world.index') }}">
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
                        <h5>{!! trans('common.edit') !!} 新闻</h5>
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
                        <form method="post" action="{{ route('news-world.update',['id'=>encodeId($view->id)]) }}"
                              class="form-horizontal">
                            {{csrf_field()}}
                            {{method_field('PUT')}}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">标题</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" value="{{ $view->title }}">
                                    @if ($errors->has('title'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('abstract') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">摘要</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="abstract"
                                           value="{{ $view->abstract }}">
                                    @if ($errors->has('abstract'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('abstract') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">新闻类别</label>
                                <div class="col-sm-10">
                                    @foreach($news_type as $item)
                                        <div class="i-checks">
                                            <label class="">
                                                <div class="iradio_square-green {{ $item->id == $view->type ? 'checked':'' }}" style="position: relative;">
                                                    <input type="radio" value="{{ $item->id}}" name="type" {{ $item->id == $view->type ? 'checked':'' }}
                                                           class="radio-class">
                                                    <ins class="iCheck-helper"></ins>
                                                </div>
                                                <i></i> {{ $item->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @if ($errors->has('class_type'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('class_type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('parsing') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                    <div id="editor">
                                        {!! $view->content !!}
                                    </div>
                                    @if ($errors->has('parsing'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('parsing') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <input type="hidden" name="content" id="content" value="">

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="">{!!trans('common.cancel')!!}</a>
                                    @if(haspermission('newscontroller.store'))
                                        <button class="btn btn-primary" id="questionSub"
                                                type="submit">{!! trans('common.edit') !!}</button>
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