<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionOptionsController extends Controller
{
    private $questionOptions;

    private $question;

    public function __construct(QuestionOptions $questionOptions, Question $question)
    {
        $this->questionOptions = $questionOptions;
        $this->question = $question;
    }

    public function index(Request $request)
    {
        $id = $request->get('q_id');

        $question = $this->question->find($request->get('q_id'));

        $data = $this->questionOptions->where('q_id', $id)->get();


        return view(getThemeView('option.lists'), ['data' => $data, 'question' => $question]);
    }

    public function create(Request $request)
    {
        $id = $request->get('q_id');

        return view(getThemeView('option.create'), ['q_id' => $id]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'q_id' => 'required',
            'content' => 'required|string',
            'is_success' => 'required|max:1|int',
        ], [
            'q_id.required' => '问题编号不能为空',
            'content.required' => '不允许为空',
            'content.string' => '类型不对',
            'is_success.required' => '题目是否正确不能为空',
            'is_success.max' => '题目是否正确数值不合法',
        ]);

        $this->questionOptions->q_id = $request->get('q_id');
        $this->questionOptions->content = $request->get('content');
        $this->questionOptions->is_success = $request->get('is_success');

        $this->questionOptions->save()
            ? flash('新增成功')->success() : flash('新增失败')->error();

        return redirect()->route('options.index', ['q_id' => $request->get('q_id')]);
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $this->questionOptions->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }
}
