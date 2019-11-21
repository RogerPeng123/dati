<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Requests\Wechat\CycleRequest;
use App\Services\Wechat\CycleService;
use App\Http\Controllers\Controller;

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

    //TODO 获取周期列表
    public function lists()
    {
        dd($this->cycleService->cycleLists()->items());
    }
}
