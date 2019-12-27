<?php

namespace App\Http\Controllers\Admin;

use App\Models\Learn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LearnController extends Controller
{
    private $learnModel;

    public function __construct(Learn $learnModel)
    {
        $this->learnModel = $learnModel;
    }

    /**
     * Display a listing of the resource.
     * Author: roger peng
     * Time: 2019/12/26 14:47
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $data = $this->learnModel->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%");
        })->paginate(10);

        return view(getThemeView('learn.lists'), ['data' => $data, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     * Author: roger peng
     * Time: 2019/12/26 15:04
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(getThemeView('learn.create'));
    }

    /**
     * Store a newly created resource in storage.
     * Author: roger peng
     * Time: 2019/12/26 15:16
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'abstract' => 'required|max:50',
            'content' => 'required'
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
            'abstract.required' => '摘要不允许为空',
            'abstract.max' => '摘要长度超出最大限制',
            'content.required' => '知识点内容不允许为空',
        ]);

        $this->learnModel->title = $request->get('title');
        $this->learnModel->abstract = $request->get('abstract');
        $this->learnModel->content = $request->get('content');
        $this->learnModel->admin_id = auth()->id();
        $this->learnModel->status = $this->learnModel::STATUS_NORMAL;

        $result = $this->learnModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('learn.index');

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
     * Time: 2019/12/26 15:21
     * @param $id
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = $this->learnModel->find(decodeId($id));

        return view(getThemeView('learn.edit'), ['view' => $data]);
    }

    /**
     * Update the specified resource in storage.
     * Author: roger peng
     * Time: 2019/12/26 21:09
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'abstract' => 'required|max:50',
            'content' => 'required'
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
            'abstract.required' => '摘要不允许为空',
            'abstract.max' => '摘要长度超出最大限制',
            'content.required' => '知识点内容不允许为空',
        ]);

        $this->learnModel = $this->learnModel->find(decodeId($id));

        $this->learnModel->title = $request->get('title');
        $this->learnModel->abstract = $request->get('abstract');
        $this->learnModel->content = $request->get('content');
        $this->learnModel->admin_id = auth()->id();
        $this->learnModel->status = $this->learnModel::STATUS_SHOW;

        $result = $this->learnModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('learn.index');
    }

    /**
     * Remove the specified resource from storage.
     * Author: roger peng
     * Time: 2019/12/26 21:14
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->learnModel->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }
}
