<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    private $questionModel;

    private $questionCycleModel;

    public function __construct(Question $question, Cycles $cycles)
    {
        $this->questionModel = $question;
        $this->questionCycleModel = $cycles;
    }

    /**
     * Display a listing of the resource.
     * Author: roger peng
     * Time: 2019/12/3 20:11
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $id = $request->get('qc_id');

        $data = $this->questionModel->where('qc_id', $id)->orderBy('created_at', 'desc')->paginate(10);

        $cycle = $this->questionCycleModel->find($id);

        return view(getThemeView('question.lists'), [
            'data' => $data, 'qc_id' => $id, 'cycle' => $cycle]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->get('qc_id');

        return view(getThemeView('question.create'), ['qc_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'type' => 'required|max:2',
        ], [
            'title.required' => '不允许为空',
            'title.max' => '超出昵称最大限制',
            'type.required' => '题目类型不能为空',
            'type.max' => '题目类型数值不合法',
        ]);

        $merge = $request->except('_token');

        $this->questionModel->title = $merge['title'];
        $this->questionModel->type = $merge['type'];
        $this->questionModel->qc_id = $merge['qc_id'];

        if ($this->questionModel->save()) {
            flash('新增成功')->success();

            $this->questionCycleModel->where('id', $merge['qc_id'])->increment('num');
        } else {
            flash('新增失败')->error();
        }


        return redirect()->route('question.index', ['qc_id' => $merge['qc_id']]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data = $this->questionModel->find(decodeId($id));

        return view(getThemeView('question.edit'), ['view' => $data, 'qc_id' => $request->get('qc_id')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string',
        ], [
            'title.required' => '不允许为空',
            'title.max' => '超出昵称最大限制',
        ]);

        $this->questionModel = $this->questionModel->find(decodeId($id));

        $this->questionModel->title = $request->get('title');
        if ($this->questionModel->type == $this->questionModel::TYPE_JUDGE) {
            $this->questionModel->judge_success = $request->get('judge_success');
        }

        $this->questionModel->save() ? flash('更新成功')->success() : flash('更新失败')->error();

        return redirect()->route('question.index', ['qc_id' => $request->get('qc_id')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = $this->questionModel->find(decodeId($id));
        if ($this->questionModel->destroy(decodeId($id))) {
            $data = ['code' => 200];

            $this->questionCycleModel->where('id', $question->qc_id)->decrement('num');

        } else {
            $data = ['code' => 500];
        }

        return response()->json($data);
    }
}
