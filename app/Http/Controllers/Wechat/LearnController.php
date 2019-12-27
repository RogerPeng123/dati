<?php

namespace App\Http\Controllers\Wechat;

use App\Services\Wechat\LearnService;
use App\Toolkit\ResponseApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LearnController extends Controller
{

    private $learnService;

    public function __construct(LearnService $learnService)
    {
        $this->learnService = $learnService;
    }

    public function lists()
    {
        return ResponseApi::ApiSuccess('success', $this->learnService->getLearnLists());
    }

    public function findLearn(int $id)
    {
        return ResponseApi::ApiSuccess('success', $this->learnService->findLearn($id));
    }
}
