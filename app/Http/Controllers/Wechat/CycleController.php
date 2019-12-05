<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Requests\Wechat\CycleRequest;
use App\Services\Wechat\CycleService;
use App\Http\Controllers\Controller;
use App\Toolkit\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
    public function lists(Request $request)
    {
        $token = $request->header('x-api-key');

        $user = Cache::get('API_TOKEN_MEMBER_' . $token);

        return ResponseApi::ApiSuccess('success', $this->cycleService->cycleLists($user));
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
        $params = $request->only('qc_id', 'body');

        $result = $this->cycleService->cycleSubmit($params, $request->header('x-api-key'));

        return ResponseApi::ApiSuccess('success', $result);
    }
}
