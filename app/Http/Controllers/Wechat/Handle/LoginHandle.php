<?php


namespace App\Http\Controllers\Wechat\Handle;


use App\Http\Controllers\Controller;
use App\Http\Requests\Wechat\LoginRequest;
use App\Services\Wechat\MemberService;
use App\Toolkit\ResponseApi;

class LoginHandle extends Controller
{
    /**
     * @var LoginRequest
     */
    private $request;

    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(LoginRequest $request, MemberService $memberService)
    {
        $this->request = $request;
        $this->memberService = $memberService;
    }

    public function __invoke()
    {
        $params = $this->request->only(['username', 'password']);

        $result = $this->memberService->memberLogin($params);

        return ResponseApi::ApiSuccess('登录成功', $result);
    }

}