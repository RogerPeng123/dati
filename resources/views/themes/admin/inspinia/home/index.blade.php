@extends('layouts.'.getTheme())
<style type="text/css">
    .data-div {
        max-height: 500px;
    }

    .data-statistical {
        height: 100%;
    }

    .statistic {
        margin: 50px 5px 5px 5px;
        border: 1px solid #e7eaec;
        background-color: #ffffff;
        height: 280px;
    }

    .statistic:hover {
        box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.9);
    }

    .statistic-title {
        padding-top: 50px;
        font-size: 22px;
        text-align: center;
        font-weight: bold;
    }

    .statistic-content {
        text-align: center;
        padding-top: 50px;
        font-size: 18px;
    }

</style>
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>后台管理系统</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:void(0);">{{ config('app.name') }}</a>
                </li>
                <li class="active">
                    <strong>控制台</strong>
                </li>
            </ol>
        </div>
        <div class="col-sm-8">
            <div class="title-action">
                {{--                <a href="{{ route('index') }}" target="_blank" class="btn btn-primary">{{ config('app.name') }}</a>--}}
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="wrapper col-lg-12 data-div">
            <div class="col-lg-3 data-statistical">
                <div class="statistic">
                    <div class="statistic-title">期题统计</div>
                    <div class="statistic-content">当前共有<span style="color: #0c9c6e"> {{ $cycles }} </span>期</div>
                </div>
            </div>
            <div class="col-lg-3 data-statistical">
                <div class="statistic">
                    <div class="statistic-title">题目统计</div>
                    <div class="statistic-content">当前共有<span style="color: #0c9c6e"> {{ $question }} </span>道题</div>
                </div>
            </div>
            <div class="col-lg-3 data-statistical">
                <div class="statistic">
                    <div class="statistic-title">会员人数</div>
                    <div class="statistic-content">当前共有<span style="color: #0c9c6e"> {{ $memberCount }} </span>人</div>
                </div>
            </div>
            <div class="col-lg-3 data-statistical">
                <div class="statistic">
                    <div class="statistic-title">达标率低于60%</div>
                    <div class="statistic-content">达标率低人数<span style="color: red;"> {{ $success_rate }} </span>人</div>
                </div>
            </div>
        </div>
        {{--        <div class="middle-box text-center animated fadeInRightBig">--}}
        {{--            <h3 class="font-bold">答题宝</h3>--}}
        {{--            <div class="error-desc">--}}
        {{--                欢迎使用答题宝后台管理系统--}}
        {{--                <br/>--}}
        {{--                <a href="{{ route('index') }}" class="btn btn-primary m-t">Dashboard</a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>
@endsection