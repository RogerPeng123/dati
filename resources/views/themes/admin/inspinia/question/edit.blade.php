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
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{!! trans('question.title') !!}</h2>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{route('cycle.index')}}">
                    <i class="fa fa-reply"></i> {!!trans('common.cancel')!!}
                </a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{!!trans('common.edit').trans('question.slug')!!}</h5>
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
                        <form method="post" action="{{route('question.update', [encodeId($view->id, 'id')])}}"
                              class="form-horizontal">
                            {{csrf_field()}}
                            {{method_field('PUT')}}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">题目</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title"
                                           value="{{old('title', $view->title)}}">
                                    @if ($errors->has('title'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <input type="hidden" name="qc_id" value="{{ $qc_id }}">

                            @if($view->type == 1)
                                <div class="form-group{{ $errors->has('judge_success') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 control-label">题目对错</label>
                                    <div class="col-sm-10">
                                        <div class="col-sm-10">
                                            <div class="i-checks">
                                                <label class="">
                                                    <div class="iradio_square-green {{ !$view->judge_success ? '':'checked' }}"
                                                         style="position: relative;">
                                                        <input type="radio" value="1" name="judge_success"
                                                               class="radio-class" {{ !$view->judge_success ? '':'checked' }}>
                                                        <ins class="iCheck-helper"></ins>
                                                    </div>
                                                    <i></i> 正确
                                                </label>
                                            </div>
                                            <div class="i-checks">
                                                <label class="">
                                                    <div class="iradio_square-green {{ !$view->judge_success ? 'checked':'' }}"
                                                         style="position: relative;">
                                                        <input type="radio" value="0" name="judge_success"
                                                               class="radio-class" {{ !$view->judge_success ? 'checked':'' }}>
                                                        <ins class="iCheck-helper"></ins>
                                                    </div>
                                                    <i></i> 错误
                                                </label>
                                            </div>
                                        </div>
                                        @if ($errors->has('judge_success'))
                                            <span class="help-block m-b-none text-danger">{{ $errors->first('judge_success') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            @endif

                            <div class="form-group{{ $errors->has('parsing') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">答案解析</label>
                                <div class="col-sm-10">
                                    <div id="editor">
                                        {!! $view->parsing !!}
                                    </div>
                                    @if ($errors->has('parsing'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('parsing') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <input type="hidden" value="{{ $view->parsing }}" name="parsing" id="parsing">


                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{ route('question.index',['qc_id'=>$qc_id]) }}">{!! trans('common.cancel') !!}</a>
                                    @if(haspermission('questioncontroller.update'))
                                        <button class="btn btn-primary" type="submit" id="editSub">
                                            {!! trans('common.edit') !!}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        $('#editSub').on('click', function () {
            $('#parsing').val(editor.txt.html());
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