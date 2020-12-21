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
            <h2>{!! trans('cycle.title') !!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:;">{!! trans('cycle.title') !!}</a>
                </li>
                <li>
                    <a href="{{ route('cycle.index') }}">{!! trans('cycle.viewList') !!}</a>
                </li>
                <li class="active">
                    <strong>{!! trans('common.create').trans('cycle.slug') !!}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{ route('cycle.index') }}">
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
                        <h5>{!! trans('common.create').trans('cycle.slug') !!}</h5>
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
                        <form method="post" action="{{ route('cycle.store') }}" class="form-horizontal">
                            {{csrf_field()}}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('cycle.titleName')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title"
                                           value="{{ $year }}年{{ $months }}月第{{ $cycle }}期答题">
                                    @if ($errors->has('title'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('cycle.isShow')}}</label>
                                <div class="col-sm-10">
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green checked" style="position: relative;">
                                                <input type="radio" value="1" name="status" class="radio-class" checked>
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i> 开放
                                        </label>
                                    </div>
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green" style="position: relative;">
                                                <input type="radio" value="0" name="status" class="radio-class">
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i> 暂时不开放
                                        </label>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group{{ $errors->has('class_type') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('cycle.class_type')}}</label>
                                <div class="col-sm-10">
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green-type checked" style="position: relative;">
                                                <input type="radio" value="1" name="class_type" class="radio-class" checked> 普通民众
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i>
                                        </label>
                                    </div>
                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green-type" style="position: relative;">
                                                <input type="radio" value="2" name="class_type" class="radio-class"> 工作人员
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i>
                                        </label>
                                    </div>

                                    <div class="i-checks">
                                        <label class="">
                                            <div class="iradio_square-green-type" style="position: relative;">
                                                <input type="radio" value="3" name="class_type" class="radio-class"> 帮扶人员
                                                <ins class="iCheck-helper"></ins>
                                            </div>
                                            <i></i>
                                        </label>
                                    </div>
                                    @if ($errors->has('class_type'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('class_type') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{ route('cycle.index') }}">{!!trans('common.cancel')!!}</a>
                                    @if(haspermission('cyclecontroller.store'))
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

            $(".iradio_square-green-type").on('click', function () {
                $('.iradio_square-green-type').each(function (key, val) {
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