<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use App\Models\Members;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CycleController extends Controller
{

    /**
     * @var Cycles
     */
    private $cycleModel;

    /**
     * @var Members
     */
    private $memberModel;

    /**
     * @var QuestionAnswer
     */
    private $questionAnswer;

    public function __construct(Cycles $cycleModel, Members $members, QuestionAnswer $questionAnswer)
    {
        $this->cycleModel = $cycleModel;
        $this->memberModel = $members;
        $this->questionAnswer = $questionAnswer;
    }

    /**
     * Display a listing of the resource.
     * Author: roger peng
     * Time: 2019/12/3 20:06
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $countMember = $this->countMember();

        $data = $this->cycleModel->when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%");
        })
            ->where('special', $this->cycleModel::TYPE_SPECIAL_NORMAL)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(getThemeView('cycle.list'), [
            'data' => $data, 'search' => $request->get('search', ''),
            'countMember' => $countMember
        ]);
    }

    //计算所有的用户
    private function countMember(): int
    {
        return Cache::remember('COUNT_MEMBER_NUM', 60 * 24 * 7, function () {
            return $this->memberModel->count();
        });
    }

    /**
     * Show the form for creating a new resource.
     * Author: roger peng
     * Time: 2019/12/3 20:07
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $time = time();
        $year = date('Y', $time);
        $months = date('m', $time);

        $cycle = $this->cycleModel->where([
            'years' => $year, 'months' => $months, 'special' => $this->cycleModel::TYPE_SPECIAL_NORMAL
        ])
            ->orderBy('created_at', 'desc')
            ->first(['cycles']);

        return view(getThemeView('cycle.create'), [
            'year' => $year, 'months' => $months, 'cycle' => $cycle ? ($cycle['cycles'] + 1) : 1]);
    }

    /**
     * Store a newly created resource in storage.
     * Author: roger peng
     * Time: 2019/12/3 20:07
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'status' => 'required|max:1',
        ], [
            'title.required' => '不允许为空',
            'title.max' => '超出标题最大限制',
            'status.required' => '请填写是否显示参数',
            'status.max' => '是否显示格式不正确',
        ]);

        $time = time();
        $year = date('Y', $time);
        $months = date('m', $time);

        $cycle = $this->cycleModel->where([
            'years' => $year, 'months' => $months, 'special' => $this->cycleModel::TYPE_SPECIAL_NORMAL
        ])->orderBy('created_at', 'desc')->first(['cycles']);

        $cycle = $cycle ? ($cycle['cycles'] + 1) : 1;

        $this->cycleModel->title = $request->get('title');
        $this->cycleModel->num = 0;
        $this->cycleModel->years = $year;
        $this->cycleModel->months = $months;
        $this->cycleModel->cycles = $cycle;
        $this->cycleModel->status = $request->get('status');
        $this->cycleModel->special = $this->cycleModel::TYPE_SPECIAL_NORMAL;
        $this->cycleModel->class_type = $request->get('class_type');

        $this->cycleModel->save()
            ? flash('新增期题成功')->success() : flash('新增期题失败')->error();

        return redirect()->route('cycle.index');
    }

    /**
     * Display the specified resource.
     * Author: roger peng
     * Time: 2019/12/3 20:07
     * @param $id
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $data = $this->cycleModel->find(decodeId($id));

        return view(getThemeView('cycle.show'), ['view' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     * Author: roger peng
     * Time: 2019/12/3 20:07
     * @param $id
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->cycleModel->find(decodeId($id));

        return view(getThemeView('cycle.edit'), ['view' => $data]);
    }

    /**
     * Update the specified resource in storage.
     * Author: roger peng
     * Time: 2019/12/3 20:06
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:20',
            'status' => 'required|max:1',
            'class_type' => 'required'
        ], [
            'title.required' => '不允许为空',
            'title.max' => '超出标题最大限制',
            'status.required' => '请填写是否显示参数',
            'status.max' => '是否显示格式不正确',
            'class_type.required' => '可查看的题目类型不能为空'
        ]);

        $this->cycleModel = $this->cycleModel->find(decodeId($id));

        $this->cycleModel->status = $request->get('status');
        $this->cycleModel->title = $request->get('title');
        $this->cycleModel->class_type = $request->get('class_type');

        $this->cycleModel->save() ? flash('更新成功')->success() : flash('更新失败')->error();

        return redirect()->route('cycle.index');
    }

    /**
     * Remove the specified resource from storage.
     * Author: roger peng
     * Time: 2019/12/3 20:07
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->cycleModel->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }

    public function rate($cid)
    {
        //理论上来讲，一个用户答题的时候  只有一次会超过80%
        $data = $this->questionAnswer->leftJoin($this->memberModel->getTable(),
            $this->memberModel->getTable() . '.' . $this->memberModel->getKeyName(),
            '=', $this->questionAnswer->getTable() . '.m_id')
            ->where($this->questionAnswer->getTable() . '.qc_id', $cid)
            ->where($this->questionAnswer->getTable() . '.correct', '>=', 80)
            ->orderBy($this->questionAnswer->getTable() . '.created_at', 'desc')
            ->paginate(10, [
                $this->questionAnswer->getTable() . '.id', $this->memberModel->getTable() . '.nickname',
                $this->questionAnswer->getTable() . '.correct', $this->questionAnswer->getTable() . '.created_at'
            ]);

        return view(getThemeView('cycle.rate'), ['data' => $data]);
    }
}
