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
            <h2>{!! trans('views.title') !!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javasctipt:;">{!! trans('home.title') !!}</a>
                </li>
                <li>
                    <a href="{{route('view.index')}}">{!! trans('views.title') !!}</a>
                </li>
                <li class="active">
                    <strong>{!!trans('common.edit').trans('views.slug')!!}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{route('view.index')}}">
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
                        <h5>{!!trans('common.create').trans('views.slug')!!}</h5>
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
                        <form method="post" action="{{route('view.update', [encodeId($view->id, 'id')])}}"
                              class="form-horizontal">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('views.name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"
                                           value="{{old('name', $view->name)}}" placeholder="{{trans('views.name')}}">
                                    @if ($errors->has('name'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('views.sex')}}</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-10">
                                        <div class="i-checks">
                                            <label class="">
                                                <div class="iradio_square-green {{ $view->sex ? '':'checked' }}" style="position: relative;">
                                                    <input type="radio" value="0" name="sex"
                                                           class="radio-class" {{ $view->sex ? '':'checked' }}>
                                                    <ins class="iCheck-helper"></ins>
                                                </div>
                                                <i></i> 女
                                            </label>
                                        </div>
                                        <div class="i-checks">
                                            <label class="">
                                                <div class="iradio_square-green {{ $view->sex ? 'checked':'' }}" style="position: relative;">
                                                    <input type="radio" value="1" name="sex"
                                                           class="radio-class" {{ $view->sex ? 'checked':'' }}>
                                                    <ins class="iCheck-helper"></ins>
                                                </div>
                                                <i></i> 男
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('sex'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('sex') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('views.age')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="age"
                                           value="{{old('age', $view->age)}}" placeholder="{{trans('views.age')}}">
                                    @if ($errors->has('age'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('age') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{ route('view.index') }}">{!! trans('common.cancel') !!}</a>
                                    @if(haspermission('viewcontroller.update'))
                                        <button class="btn btn-primary" type="submit">
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