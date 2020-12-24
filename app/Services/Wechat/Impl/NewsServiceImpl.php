<?php


namespace App\Services\Wechat\Impl;


use App\Models\News;
use App\Models\NewsType;
use App\Services\Wechat\NewsService;

class NewsServiceImpl implements NewsService
{
    /**
     * @var NewsType
     */
    private $newsTypeModel;

    /**
     * @var  News
     */
    private $newsModel;

    public function __construct(NewsType $newsTypeModel, News $newsModel)
    {
        $this->newsTypeModel = $newsTypeModel;
        $this->newsModel = $newsModel;
    }

    function getTypes()
    {
        return $this->newsTypeModel->get(['id', 'title'])->toArray();
    }

    function getNews(int $type)
    {
        return $this->newsModel
            ->where('type', $type)
            ->where('status', $this->newsModel::STATUS_SHOW)
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10, ['id', 'title', 'abstract'])->toArray();
    }

    function findNews(int $id)
    {
        return $this->newsModel->find($id);
    }


}