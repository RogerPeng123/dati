<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialController extends Controller
{
    private $cycleModel;

    public function __construct(Cycles $cycleModel)
    {
        $this->cycleModel = $cycleModel;
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

        $data = $this->cycleModel->when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%");
        })
            ->where('special', $this->cycleModel::TYPE_SPECIAL_TOP)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(getThemeView('special.list'), [
            'data' => $data, 'search' => $request->get('search', '')
        ]);
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
            'years' => $year, 'months' => $months, 'special' => $this->cycleModel::TYPE_SPECIAL_TOP
        ])
            ->orderBy('created_at', 'desc')
            ->first(['cycles']);

        return view(getThemeView('special.create'), [
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
            'years' => $year, 'months' => $months, 'special' => $this->cycleModel::TYPE_SPECIAL_TOP
        ])->orderBy('created_at', 'desc')->first(['cycles']);

        $cycle = $cycle ? ($cycle['cycles'] + 1) : 1;

        $this->cycleModel->title = $request->get('title');
        $this->cycleModel->num = 0;
        $this->cycleModel->years = $year;
        $this->cycleModel->months = $months;
        $this->cycleModel->cycles = $cycle;
        $this->cycleModel->status = $request->get('status');
        $this->cycleModel->special = $this->cycleModel::TYPE_SPECIAL_TOP;

        $this->cycleModel->save()
            ? flash('新增期题成功')->success() : flash('新增期题失败')->error();

        return redirect()->route('special.index');
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

        return view(getThemeView('special.show'), ['view' => $data]);
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

        return view(getThemeView('special.edit'), ['view' => $data]);
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
        ], [
            'title.required' => '不允许为空',
            'title.max' => '超出标题最大限制',
            'status.required' => '请填写是否显示参数',
            'status.max' => '是否显示格式不正确',
        ]);

        $this->cycleModel = $this->cycleModel->find(decodeId($id));

        $this->cycleModel->status = $request->get('status');
        $this->cycleModel->title = $request->get('title');

        $this->cycleModel->save() ? flash('更新成功')->success() : flash('更新失败')->error();

        return redirect()->route('special.index');
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
}
