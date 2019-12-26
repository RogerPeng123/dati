<?php

namespace App\Http\Controllers\Admin;

use App\Models\Members;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MembersController extends Controller
{
    private $memebrModel;

    public function __construct(Members $memebrModel)
    {
        $this->memebrModel = $memebrModel;
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

        return view(getThemeView('members.lists'), ['data' => $data, 'search' => $search]);
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
}
