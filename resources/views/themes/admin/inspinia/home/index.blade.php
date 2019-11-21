@extends('layouts.'.getTheme())
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
                <a href="{{ route('index') }}" target="_blank" class="btn btn-primary">{{ config('app.name') }}</a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="middle-box text-center animated fadeInRightBig">
            <h3 class="font-bold">This is page content</h3>
            <div class="error-desc">
                You can create here any grid layout you want. And any variation layout you imagine:) Check out
                main dashboard and other site. It use many different layout.
                <br/><a href="{{ route('index') }}" class="btn btn-primary m-t">Dashboard</a>
            </div>
        </div>
    </div>
@endsection