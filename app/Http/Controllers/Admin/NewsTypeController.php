<?php

namespace App\Http\Controllers\Admin;

use App\Models\Learn;
use App\Models\NewsType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  新闻中心
 *
 * Class NewsController
 * @package App\Http\Controllers\Admin
 */
class NewsTypeController extends Controller
{
    private $newsTypeModel;

    public function __construct(NewsType $newsType)
    {
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

        $data = $this->newsTypeModel->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%");
        })->orderBy('created_at', 'desc')->paginate(10);

        return view(getThemeView('news_type.lists'), ['data' => $data, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     * Author: roger peng
     * Time: 2019/12/26 15:04
     * @return \BladeView|bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(getThemeView('news_type.create'));
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
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
        ]);

        $this->newsTypeModel->title = $request->get('title');

        $result = $this->newsTypeModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('news-type.index');

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
        $data = $this->newsTypeModel->find(decodeId($id));

        return view(getThemeView('news_type.edit'), ['view' => $data]);
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
        ], [
            'title.required' => '知识点标题不允许为空',
            'title.max' => '知识点标题超出最大限制',
        ]);

        $this->newsTypeModel = $this->newsTypeModel->find(decodeId($id));

        $this->newsTypeModel->title = $request->get('title');

        $result = $this->newsTypeModel->save();

        $result ? flash()->success('新增成功') : flash()->error('新增失败');

        return redirect()->route('news-type.index');
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
        $this->newsTypeModel->destroy(decodeId($id)) ? $data = ['code' => 200] : $data = ['code' => 500];

        return response()->json($data);
    }
}
