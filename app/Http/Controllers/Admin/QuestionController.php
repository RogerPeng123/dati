<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use App\Models\Question;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    private $questionModel;

    private $questionCycleModel;

    private $questionOptionsModel;

    public function __construct(Question $question, Cycles $cycles, QuestionOptions $questionOptionsModel)
    {
        $this->questionModel = $question;
        $this->questionCycleModel = $cycles;
        $this->questionOptionsModel = $questionOptionsModel;
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


        $cycles = $this->questionCycleModel->where(['special' => $this->questionCycleModel::TYPE_SPECIAL_TOP])
            ->get(['id', 'title']);

        $cycle = $this->questionCycleModel->find($id);

        return view(getThemeView('question.lists'), [
            'data' => $data, 'qc_id' => $id, 'cycle' => $cycle, 'cycles' => $cycles]);

    }

    /**
     * Show the form for creating a new resource.
     * Author: roger peng
     * Time: 2019/12/26 09:58
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $id = $request->get('qc_id');

        return view(getThemeView('question.create'), ['qc_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     * Author: roger peng
     * Time: 2019/12/26 10:09
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        $this->questionModel->parsing = $merge['parsing'];

        if ($merge['type'] == 1) {
            $this->questionModel->judge_success = $merge['judge_success'];
        }

        if ($this->questionModel->save()) {

            flash('新增成功')->success();

            if ($merge['type'] != 1) {
                $successStr = $request->get('success');
                $successArray = explode(',', $successStr);
                $options = [];
                foreach ($request->get('options') as $key => $item) {
                    if (empty($item)) {
                        continue;
                    }

                    $options[] = [
                        'q_id' => $this->questionModel->id,
                        'content' => $item,
                        'is_success' => in_array(($key + 1), $successArray) ? 1 : 0,
                        'created_at' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time())
                    ];
                }

                $this->questionOptionsModel->insert($options);
            }

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
     * Author: roger peng
     * Time: 2019/12/26 10:10
     * @param $id
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request)
    {
        $data = $this->questionModel->find(decodeId($id));

        return view(getThemeView('question.edit'), ['view' => $data, 'qc_id' => $request->get('qc_id')]);
    }

    /**
     * Update the specified resource in storage.
     * Author: roger peng
     * Time: 2019/12/26 10:09
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
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
        $this->questionModel->parsing = $request->get('parsing');

        if ($this->questionModel->type == $this->questionModel::TYPE_JUDGE) {
            $this->questionModel->judge_success = $request->get('judge_success');
        }

        $this->questionModel->save() ? flash('更新成功')->success() : flash('更新失败')->error();

        return redirect()->route('question.index', ['qc_id' => $request->get('qc_id')]);
    }

    /**
     * Remove the specified resource from storage.
     * Author: roger peng
     * Time: 2019/12/26 10:10
     * @param $id
     * @return \Illuminate\Http\JsonResponse
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

    //转移至专项问题
    public function changeSpecial(Request $request)
    {
        $params = $request->only(['special_id', 'question_id']);

        $question = $this->questionModel->find($params['question_id']);

        $newQuestion = $this->questionModel->insertGetId([
            'title' => $question->title, 'type' => $question->type, 'qc_id' => $params['special_id'],
            'parsing' => $question->parsing, 'judge_success' => $question->judge_success,
            'created_at' => date('Y-m-d H:i:s', time()), 'updated_at' => date('Y-m-d H:i:s', time())
        ]);

        if ($question->type == $this->questionModel::TYPE_CHOOSE
            || $question->type == $this->questionModel::TYPE_MULTI) {

            $options = [];
            foreach ($question->questionOptions as $key => $item) {
                $options[$key]['q_id'] = $newQuestion;
                $options[$key]['content'] = $item->content;
                $options[$key]['is_success'] = $item->is_success;
                $options[$key]['created_at'] = date('Y-m-d H:i:s', time());
                $options[$key]['updated_at'] = date('Y-m-d H:i:s', time());
            }
            QuestionOptions::insert($options);
        }

        $this->questionCycleModel->where('id', $params['special_id'])->increment('num');

        return response()->json(['message' => '执行成功', 'code' => 200]);
    }
}
