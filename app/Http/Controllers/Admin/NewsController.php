<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\NewsType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  新闻中心
 *
 * Class NewsController
 * @package App\Http\Controllers\Admin
 */
class NewsController extends Controller
{
    private $newsModel;

    private $newsTypeModel;

    public function __construct(News $newsModel, NewsType $newsType)
    {
        $this->newsModel = $newsModel;

        $this->newsTypeModel = $newsType;
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

        $data = $this->newsModel->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view(getThemeView('news.lists'), ['data' => $data, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     * Author: roger peng
     * Time: 2019/12/26 15:04
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $newsTypes = $this->newsTypeModel->get();

        return view(getThemeView('news.create'), ['news_type' => $newsTypes]);
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
            'type' => 'required',
            'content' => 'required'
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
            'abstract.required' => '摘要不允许为空',
            'abstract.max' => '摘要长度超出最大限制',
            'content.required' => '知识点内容不允许为空',
            'type.required' => '新闻类别不能为空'
        ]);

        $this->newsModel->title = $request->get('title');
        $this->newsModel->abstract = $request->get('abstract');
        $this->newsModel->content = $request->get('content');
        $this->newsModel->admin_id = auth()->id();
        $this->newsModel->status = $this->newsModel::STATUS_NORMAL;
        $this->newsModel->type = $request->get('type');

        $result = $this->newsModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('news-world.index');

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
        $data = $this->newsModel->find(decodeId($id));

        $newsTypes = $this->newsTypeModel->get();

        return view(getThemeView('news.edit'), ['view' => $data, 'news_type' => $newsTypes]);
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
            'type' => 'required',
            'content' => 'required'
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
            'abstract.required' => '摘要不允许为空',
            'abstract.max' => '摘要长度超出最大限制',
            'content.required' => '知识点内容不允许为空',
            'type.required' => '可查看用户类别不能为空'
        ]);

        $this->newsModel = $this->newsModel->find(decodeId($id));

        $this->newsModel->title = $request->get('title');
        $this->newsModel->abstract = $request->get('abstract');
        $this->newsModel->content = $request->get('content');
        $this->newsModel->admin_id = auth()->id();
        $this->newsModel->status = $this->newsModel::STATUS_SHOW;
        $this->newsModel->type = $request->get('type');

        $result = $this->newsModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('news-world.index');
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
        $this->newsModel->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }
}
