<?php

namespace App\Http\Controllers\Wechat;

use App\Services\Wechat\MemberService;
use App\Http\Controllers\Controller;
use App\Toolkit\ResponseApi;

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
}
