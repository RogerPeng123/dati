@extends('layouts.'.getTheme())
@section('css')
    <link href="{{asset(getThemeAssets('iCheck/custom.css', true))}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>会员信息</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javasctipt:;">{!! trans('home.title') !!}</a>
                </li>
                <li>
                    <a href="{{route('members.index')}}">会员列表</a>
                </li>
                <li class="active">
                    <strong>{!!trans('common.edit')!!}会员</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                <a class="btn btn-white" href="{{route('members.index')}}">
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
                        <h5>{!!trans('common.edit')!!} 会员</h5>
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
                        <form method="post" action="{{route('members.update', [encodeId($view->id, 'id')])}}"
                              class="form-horizontal">
                            {{csrf_field()}}
                            {{method_field('PUT')}}

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">手机号码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username"
                                           value="{{old('username', $view->username)}}" placeholder="手机号码">
                                    @if ($errors->has('username'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">会员昵称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nickname"
                                           value="{{old('nickname', $view->nickname)}}" placeholder="会员昵称">
                                    @if ($errors->has('nickname'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('nickname') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('integral') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">会员积分</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="integral"
                                           value="{{old('integral', $view->integral)}}" placeholder="会员积分">
                                    @if ($errors->has('integral'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('integral') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('questions_num') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">答题次数</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="questions_num"
                                           value="{{old('questions_num', $view->questions_num)}}" placeholder="答题次数">
                                    @if ($errors->has('questions_num'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('questions_num') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white"
                                       href="{{ route('members.index') }}">{!! trans('common.cancel') !!}</a>
                                    @if(haspermission('memberscontroller.update'))
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