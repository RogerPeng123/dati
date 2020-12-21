<?php


namespace App\Http\Controllers\Wechat\Handle;


use App\Http\Controllers\Controller;
use App\Http\Requests\Wechat\RegisterRequest;
use App\Services\Wechat\MemberService;
use App\Toolkit\ResponseApi;

class RegisterHandle extends Controller
{
    /**
     * @var RegisterRequest
     */
    private $request;

    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(RegisterRequest $request, MemberService $memberService)
    {
        $this->request = $request;
        $this->memberService = $memberService;
    }

    public function __invoke()
    {
        $param = $this->request->only(['username', 'nickname', 'password', 'type']);

        return ResponseApi::ApiSuccess('注册成功', $this->memberService->registerMember($param));
    }
}