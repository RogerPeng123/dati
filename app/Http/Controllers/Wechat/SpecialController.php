<?php

namespace App\Http\Controllers\Wechat;

use App\Services\Wechat\CycleService;
use App\Http\Controllers\Controller;
use App\Toolkit\ResponseApi;

class SpecialController extends Controller
{
    private $cycleService;

    public function __construct(CycleService $cycleService)
    {
        $this->cycleService = $cycleService;
    }

    public function lists()
    {
        return ResponseApi::ApiSuccess('success', $this->cycleService->getCycleSpecialList());
    }

    public function questionNext()
    {
        return ResponseApi::ApiSuccess('success', $this->cycleService->getCycleSpecialNextList());
    }
}
