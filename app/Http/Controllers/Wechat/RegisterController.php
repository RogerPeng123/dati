<?php


namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Requests\Wechat\RegisterRequest;
use App\Services\Wechat\MemberService;
use App\Toolkit\ResponseApi;

class RegisterController extends Controller
{
    private $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function register(RegisterRequest $request)
    {
        $param = $request->only(['username', 'nickname', 'password']);

        return ResponseApi::ApiSuccess('注册成功', $this->memberService->registerMember($param));
    }
}