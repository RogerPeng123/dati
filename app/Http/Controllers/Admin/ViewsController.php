<?php

namespace App\Http\Controllers\Admin;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Test = new Test();

        $data = $Test->getIndex($request->get('search', ''));

        return view(getThemeView('view.list'), [
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
        return view(getThemeView('view.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
            'sex' => 'required|max:1',
            'age' => 'required|max:3'
        ], [
            'name.required' => '不允许为空',
            'name.max' => '超出昵称最大限制',
            'sex.required' => '请填写性别参数',
            'sex.max' => '性别格式不正确',
            'age.required' => '请填写年龄参数',
            'age.max' => '年龄参数超出最大限制'
        ]);

        $merge = $request->except('_token');

        $Test = new Test();

        $Test->addData($merge) ? flash('新增视图成功')->success() : flash("新增视图失败")->error();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Test = new Test();
        $data = $Test->show($id);

        return view(getThemeView('view.show'), ['view' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Test = new Test();
        $data = $Test->show($id);

        return view(getThemeView('view.edit'), ['view' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
            'sex' => 'required|max:1',
            'age' => 'required|max:3'
        ], [
            'name.required' => '不允许为空',
            'name.max' => '超出昵称最大限制',
            'sex.required' => '请填写性别参数',
            'sex.max' => '性别格式不正确',
            'age.required' => '请填写年龄参数',
            'age.max' => '年龄参数超出最大限制'
        ]);

        $Test = new Test();

        $result = $Test->edit($id, $request->except(['_token', '_method']));
        $result ? flash('更新成功')->success() : flash('更新失败')->error();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
