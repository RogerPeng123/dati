@extends('layouts.'.getTheme())
@section('css')
    <link href="{{asset(getThemeAssets('iCheck/custom.css', true))}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>期题管理</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:;">{!! trans('home.title') !!}</a>
                </li>
                <li>
                    <a href="{{ route('special.index') }}">专项管理</a>
                </li>
                <li class="active">
                    <strong>{!!trans('common.show')!!}专项管理详情</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{route('special.index')}}">
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
                        <h5>{!! trans('common.show').$view->title !!}</h5>
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
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">专项答题标题</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$view->title}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">专项答题标题数量</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{ $view->num}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{trans('cycle.status')}}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$view->status ? '开放' : '不开放'}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{trans('cycle.created_at')}}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$view->created_at}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{trans('cycle.updated_at')}}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$view->updated_at}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{route('special.index')}}">{!!trans('common.cancel')!!}</a>
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
@endsection