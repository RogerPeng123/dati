<?php

namespace App\Http\Controllers\Wechat;

use App\Services\Wechat\NewsService;
use App\Toolkit\ResponseApi;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     *
     * 新闻分类数据
     * Author: roger peng
     * Time: 2020/12/21 上午11:54
     * @return \Illuminate\Http\JsonResponse
     */
    public function types()
    {
        return ResponseApi::ApiSuccess('success', $this->newsService->getTypes());
    }

    /**
     *
     * 根据新闻分类获取新闻列表
     * Author: roger peng
     * Time: 2020/12/21 下午12:13
     * @param int $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNews(int $type)
    {
        return ResponseApi::ApiSuccess('success', $this->newsService->getNews($type));
    }

    /**
     *
     * 获取新闻详情
     * Author: roger peng
     * Time: 2020/12/21 下午12:13
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findNews(int $id)
    {
        return ResponseApi::ApiSuccess('success', $this->newsService->findNews($id));
    }
}
