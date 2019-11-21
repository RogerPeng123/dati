<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cycles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CycleController extends Controller
{
    private $cycleModel;

    public function __construct(Cycles $cycleModel)
    {
        $this->cycleModel = $cycleModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $data = $this->cycleModel->when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view(getThemeView('cycle.list'), [
            'data' => $data, 'search' => $request->get('search', '')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $time = time();
        $year = date('Y', $time);
        $months = date('m', $time);

        $cycle = $this->cycleModel->where(['years' => $year, 'months' => $months])
            ->orderBy('created_at', 'desc')
            ->first(['cycles']);

        return view(getThemeView('cycle.create'), [
            'year' => $year, 'months' => $months, 'cycle' => $cycle ? ($cycle['cycles'] + 1) : 1]);
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
            'title' => 'required|max:20',
            'status' => 'required|max:1',
        ], [
            'name.required' => '不允许为空',
            'name.max' => '超出标题最大限制',
            'status.required' => '请填写是否显示参数',
            'status.max' => '是否显示格式不正确',
        ]);

        $time = time();
        $year = date('Y', $time);
        $months = date('m', $time);

        $cycle = $this->cycleModel->where(['years' => $year, 'months' => $months])
            ->orderBy('created_at', 'desc')->first(['cycles']);

        $cycle = $cycle ? ($cycle['cycles'] + 1) : 1;

        $this->cycleModel->title = "{$year}年{$months}月第{$cycle}期答题";
        $this->cycleModel->num = 0;
        $this->cycleModel->years = $year;
        $this->cycleModel->months = $months;
        $this->cycleModel->cycles = $cycle;
        $this->cycleModel->status = $request->get('status');

        $this->cycleModel->save()
            ? flash('新增期题成功')->success() : flash('新增期题失败')->error();

        return redirect()->route('cycle.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->cycleModel->find(decodeId($id));

        return view(getThemeView('cycle.show'), ['view' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->cycleModel->find(decodeId($id));

        return view(getThemeView('cycle.edit'), ['view' => $data]);
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
            'status' => 'required|max:1',
        ], [
            'status.required' => '请填写是否显示参数',
            'status.max' => '是否显示格式不正确',
        ]);

        $this->cycleModel = $this->cycleModel->find(decodeId($id));

        $this->cycleModel->status = $request->get('status');

        $this->cycleModel->save() ? flash('更新成功')->success() : flash('更新失败')->error();

        return redirect()->route('cycle.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cycleModel->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }
}
