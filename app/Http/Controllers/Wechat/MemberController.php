<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Requests\Wechat\MemberRequest;
use App\Services\Wechat\MemberService;
use App\Http\Controllers\Controller;
use App\Toolkit\ResponseApi;
use Illuminate\Support\Facades\Crypt;

class MemberController extends Controller
{
    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function info()
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->memberInfo());
    }

    public function answerLists()
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->getMemberAnswerLog());

    }

    public function rankIntegral()
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->getIntegralRank());
    }

    public function learnLists()
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->getMemberLearnLog());
    }

    public function integralLogs()
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->getMemberIntegralLogs());
    }

    public function changeMobile(MemberRequest $request)
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->changeMemberInfo([
            'username' => $request->get('mobile')
        ]));
    }

    public function changePassword(MemberRequest $request)
    {
        return ResponseApi::ApiSuccess('success', $this->memberService->changeMemberInfo([
            'password' => Crypt::encryptString($request->get('password'))
        ]));
    }
}
