@extends('layouts.'.getTheme())
@section('css')
    <link href="{{ asset(getThemeAssets('dataTables/datatables.min.css', true)) }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>会员管理</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:void(0);">会员管理</a>
                </li>
                <li>
                    <a href="{{ route('members.index') }}">会员列表</a>
                </li>
                <li class="active">
                    <strong>答题记录</strong>
                </li>
            </ol>
        </div>

    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>答题记录</h5>
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

                        <div id="dataTableBuilder_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                           id="dataTableBuilder" role="grid" aria-describedby="dataTableBuilder_info">
                                        <thead>
                                        <tr role="row">
                                            <th>序号</th>
                                            <th>答题标题</th>
                                            <th>答对数量</th>
                                            <th>总共数量</th>
                                            <th>正确率</th>
                                            <th>答题时间</th>
                                            <th>修改时间</th>
{{--                                            <th>操作</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if($data->total())
                                            @foreach($data as $item)
                                                <tr role="row" class="odd">
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->questionCycle->title }}</td>
                                                    <td>{{ $item->success_questions }}</td>
                                                    <td>{{ $item->errors_questions + $item->success_questions }}</td>
                                                    <td>{{ $item->correct }}%</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->updated_at }}</td>
{{--                                                    <td>--}}
{{--                                                        <a href="{{ route('members.edit',['id'=>encodeId($item->id)]) }}"--}}
{{--                                                           class="btn btn-xs btn-outline btn-warning tooltips">--}}
{{--                                                            <i class="fa fa-edit"></i>--}}
{{--                                                        </a>--}}
{{--                                                        <a href="{{ route('members.logs',['id'=>encodeId($item->id)]) }}"--}}
{{--                                                           class="btn btn-xs btn-outline btn-primary tooltips">--}}
{{--                                                            答题记录--}}
{{--                                                        </a>--}}
{{--                                                    </td>--}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr role="row" class="odd">
                                                <td colspan="6">没有数据</td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_paginate paging_simple_numbers"
                                         id="dataTableBuilder_paginate">
                                        {!! $data->links() !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection