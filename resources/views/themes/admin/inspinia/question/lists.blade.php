@extends('layouts.'.getTheme())
@section('css')
    <link href="{{ asset(getThemeAssets('dataTables/datatables.min.css', true)) }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $cycle->title }}</h2>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                @if(haspermission('questioncontroller.create'))
                    <a href="{{ route('question.create',['qc_id'=>$cycle->id]) }}" class="btn btn-info">
                        <i class="fa fa-plus"></i> {!! trans('common.create').trans('question.slug') !!}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{!! trans('question.title') !!}</h5>
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
                                            <th>题目</th>
                                            <th>问题类型</th>
                                            <th>创建时间</th>
                                            <th>修改时间</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if($data->total())
                                            @foreach($data as $item)
                                                <tr role="row" class="odd">
                                                    <td>{{ $item->id }}</td>
                                                    <td>
                                                        @if($item->type == \App\Models\Question::TYPE_JUDGE)
                                                            <a href="{{ route('question.edit',['id'=>encodeId($item->id),'qc_id'=>$cycle->id]) }}"
                                                               target="_blank">
                                                                {{ $item->title }}
                                                            </a>
                                                        @elseif($item->type == \App\Models\Question::TYPE_CHOOSE)
                                                            <a href="{{ route('options.index',['q_id'=>$item->id]) }}"
                                                               target="_blank">
                                                                {{ $item->title }}
                                                            </a>
                                                        @elseif($item->type == \App\Models\Question::TYPE_MULTI)
                                                            <a href="{{ route('options.index',['q_id'=>$item->id]) }}"
                                                               target="_blank">
                                                                {{ $item->title }}
                                                            </a>
                                                        @else
                                                            {{ $item->title }}
                                                        @endif
                                                    </td>
                                                    @if($item->type == \App\Models\Question::TYPE_JUDGE)
                                                        <td>判断题</td>
                                                    @elseif($item->type == \App\Models\Question::TYPE_CHOOSE)
                                                        <td>单择题</td>
                                                    @elseif($item->type == \App\Models\Question::TYPE_MULTI)
                                                        <td>多选题</td>
                                                    @else
                                                        <td>未知类型</td>
                                                    @endif
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->updated_at }}</td>
                                                    <td>
                                                        @if($cycle->special == \App\Models\Cycles::TYPE_SPECIAL_NORMAL)
                                                            <a data-id="{{ $item->id }}"
                                                               class="btn btn-xs btn-outline btn-primary tooltips change-special">
                                                                移至专项
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('question.edit',['id'=>encodeId($item->id),'qc_id'=>$cycle->id]) }}"
                                                           class="btn btn-xs btn-outline btn-warning tooltips">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:;"
                                                           destroy-url="{{ route('question.destroy',['id'=>encodeId($item->id)]) }}"
                                                           class="btn btn-xs btn-outline btn-danger tooltips destroy_item">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
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
                                        {!! $data->appends(['qc_id'=>$qc_id])->links() !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">加入专项答题</h4>
                </div>
                <div class="modal-body">
                    <form method="post" class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">选择专项答题</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="special_select">
                                    @foreach($cycles as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <input id="question_id" type="hidden">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="sub-add-special">提交更改</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection
@section('js')
    <script src="{{asset(getThemeAssets('dataTables/datatables.min.js', true))}}"></script>
    <script src="{{asset(getThemeAssets('layer/layer.js', true))}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.change-special').on('click', function () {
                $('#question_id').val($(this).attr('data-id'));
                $('#myModal').modal('show');
            });

            $('#sub-add-special').on('click', function () {
                let special_id = $('#special_select').val();
                let question_id = $('#question_id').val();


                let url = "{{ route('question.change.special') }}";
                let data = {question_id: question_id, special_id: special_id, _token: "{{csrf_token()}}"};

                $.post(url, data, function (e) {
                    if (e.code == 200) {
                        layer.alert(e.message);
                        $('#myModal').modal('hide');
                    }
                }, 'json');

            });
        })

        $(document).on('click', '.destroy_item', function () {
            var _item = $(this);
            var title = "{{trans('common.deleteTitle').trans('cycle.slug')}}？";
            layer.confirm(title, {
                btn: ['{{trans('common.yes')}}', '{{trans('common.no')}}'],
                icon: 5
            }, function (index) {
                // _item.children('form').submit();
                let url = _item.attr('destroy-url');

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {_token: "{{ csrf_token() }}"},
                    success: function (res) {
                        if (res.code == 200) {
                            _item.parent().parent().remove();
                        }
                    }
                });

                layer.close(index);
            });
        });
    </script>
@endsection