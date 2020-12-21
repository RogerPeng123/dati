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
                    <strong>新闻类别</strong>
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
                        <h5>{!! trans('common.edit') !!} 新闻类别</h5>
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
                        <form method="post" action="{{ route('news-type.update',['id'=>encodeId($view->id)]) }}"
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

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="">{!!trans('common.cancel')!!}</a>
                                    @if(haspermission('newstypecontroller.store'))
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