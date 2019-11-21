<?php


namespace App\Http\Controllers\Wechat\Handle;

use App\Exceptions\ApiResponseExceptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wechat\LogoutRequest;
use App\Models\Members;
use App\Toolkit\ResponseApi;
use Illuminate\Support\Facades\Cache;

class LogoutHandle extends Controller
{
    private $request;

    private $memberModel;

    public function __construct(LogoutRequest $request, Members $memberModel)
    {
        $this->request = $request;
        $this->memberModel = $memberModel;
    }

    /**
     *
     * Author: roger peng
     * Time: 2019/11/20 22:21
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function __invoke()
    {
        $token = $this->request->get('api_token');

        $this->memberModel = $this->memberModel->where(['api_token' => $token])->first();

        throw_unless($this->memberModel, ApiResponseExceptions::class, '当前用户没有登录');

        $this->memberModel->api_token = null;

        $this->memberModel->save();

        Cache::forget('API_TOKEN_MEMBER_' . $token);

        return ResponseApi::ApiSuccess('退出成功');
    }
}
