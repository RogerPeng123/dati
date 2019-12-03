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
            <h2>新增答案</h2>

        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{ route('options.index',['q_id'=>$q_id]) }}">
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
                        <h5>{!! trans('common.create') !!} 答案</h5>
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
                        <form method="post" action="{{ route('options.store') }}" class="form-horizontal">
                            {{csrf_field()}}

                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">答案</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="content">
                                    @if ($errors->has('content'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('content') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group{{ $errors->has('is_success') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">是否正确</label>
                                <div class="col-sm-10">
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green checked" style="position: relative;">
                                                <input type="radio" value="1" name="is_success" class="radio-class" checked>
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i> 正确
                                        </label>
                                    </div>
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green" style="position: relative;">
                                                <input type="radio" value="0" name="is_success" class="radio-class">
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i> 错误
                                        </label>
                                    </div>
                                    @if ($errors->has('is_success'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('is_success') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <input type="hidden" name="q_id" value="{{ $q_id }}">

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{ route('options.index',['q_id'=>$q_id]) }}">{!!trans('common.cancel')!!}</a>
                                    @if(haspermission('questionoptionscontroller.store'))
                                        <button class="btn btn-primary"
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