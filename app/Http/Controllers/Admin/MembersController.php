<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use App\Models\Members;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MembersController extends Controller
{
    private $memebrModel;

    private $questionAnswerModel;

    private $cycleModel;

    public function __construct(Members $memebrModel, QuestionAnswer $questionAnswerModel, Cycles $cycles)
    {
        $this->memebrModel = $memebrModel;
        $this->questionAnswerModel = $questionAnswerModel;
        $this->cycleModel = $cycles;
    }

    /**
     * Display a listing of the resource.
     * Author: roger peng
     * Time: 2019/12/26 10:37
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = $this->memebrModel
            ->when($search, function ($query) use ($search) {
                return $query->where('username', 'like', "%$search%");
            })->orderBy('created_at', 'desc')->paginate(10);


        foreach ($data as $item) {
            $item->pending = $this->pending($item->id);
            $item->completion = $this->completion($item->pending);
            $item->success_rate = $this->successRate($item->id);
        }

        return view(getThemeView('members.lists'), ['data' => $data, 'search' => $search]);
    }

    //达标率
    private function successRate(int $mId): float
    {
        $cycles = $this->cycleModel->get(['id']);

        $count = 0;
        $corrects = 0;

        foreach ($cycles as $cycle) {
            $exists = $this->existesCycle($cycle->id, $mId);
            if (!$exists) {
                $count++;

                $corrects += $this->correctCount($cycle->id);
            }
        }

        if ($corrects != 0) {
            return round($corrects / $count, 2);
        }

        return 0;
    }

    private function correctCount(int $id): float
    {
        $member = $this->memebrModel;
        return Cache::remember('QUESTION_ANSWER_CORRECT_' . $id, 60 * 24 * 30, function () use ($id, $member) {
            $answer = $this->questionAnswerModel->where(['qc_id' => $id, 'm_id' => $member->id])
                ->orderBy('id', 'desc')->first(['correct']);

            return $answer['correct'];
        });
    }

    //计算出完成率
    private function completion(int $pendint): float
    {
        $count = $this->cycleModel->count();

        return round(($count - $pendint) / $count * 100, 2);
    }

    //计算当前用户在题库中没有作答的题目数量
    private function pending(int $mId): int
    {
        $cycles = $this->cycleModel->get(['id']);

        $num = 0;
        foreach ($cycles as $cycle) {
            $exists = $this->existesCycle($cycle->id, $mId);
            if ($exists) {
                $num++;
            }
        }

        return $num;
    }

    //判断当前用户对于答题是否作答
    private function existesCycle($qcId, $mId): int
    {
        return Cache::remember('QUESTION_ANSWER_EXISTS_QC_ID_' . $qcId . '_MID_' . $mId, 60 * 24 * 30,
            function () use ($qcId, $mId) {
                if ($this->questionAnswerModel->where(['qc_id' => $qcId, 'm_id' => $mId])->exists()) {
                    return 0;
                } else {
                    return 1;
                }
            });
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }

//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }

//    /**
//     * Display the specified resource.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     * Author: roger peng
     * Time: 2019/12/26 10:56
     * @param $id
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->memebrModel->find(decodeId($id));

        return view(getThemeView('members.edit'), ['view' => $data]);
    }

    /**
     * Update the specified resource in storage.
     * Author: roger peng
     * Time: 2019/12/26 11:07
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username' => ['required', 'max:11', 'unique:members'],
            'nickname' => ['required', 'max:20', 'unique:members'],
        ], [
            'username.required' => '电话号码不允许为空',
            'username.max' => '电话号码最大限制',
            'username.unique' => '手机号码已被注册',
            'nickname.required' => '请填写昵称参数',
            'nickname.max' => '昵称超出最大限制',
            'nickname.unique' => '该昵称已被注册',
        ]);

        $this->memebrModel = $this->memebrModel->find(decodeId($id));

        $this->memebrModel->username = $request->get('username');
        $this->memebrModel->nickname = $request->get('nickname');

        $result = $this->memebrModel->save();

        $result ? flash('更新成功')->success() : flash('更新失败')->error();

        return back();
    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }

    public function logs($id)
    {
        $db = $this->questionAnswerModel->where('m_id', decodeId($id))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(getThemeView('members.logs'), ['data' => $db]);
    }
}
