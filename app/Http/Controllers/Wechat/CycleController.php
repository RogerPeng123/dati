<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Requests\Wechat\CycleRequest;
use App\Services\Wechat\CycleService;
use App\Http\Controllers\Controller;
use App\Toolkit\ResponseApi;

class CycleController extends Controller
{
    /**
     * @var CycleService
     */
    private $cycleService;

    public function __construct(CycleService $cycleService)
    {
        $this->cycleService = $cycleService;
    }

    /**
     * 获取周期列表
     * Author: roger peng
     * Time: 2019/11/21 23:03
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        return ResponseApi::ApiSuccess('success', $this->cycleService->cycleLists());
    }

    /**
     * 获取单期的问题信息
     * Author: roger peng
     * Time: 2019/11/21 23:04
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function question($id)
    {
        return ResponseApi::ApiSuccess('success', $this->cycleService->cycleQuestion($id));
    }

    public function quetionSubmit(CycleRequest $request)
    {

    }
}
