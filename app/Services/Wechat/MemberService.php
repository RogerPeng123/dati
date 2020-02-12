<?php


namespace App\Services\Wechat;


use App\Models\Members;

interface MemberService
{
    function registerMember(array $params);

    function memberLogin(array $params);

    function memberInfo();

    function getMemberAnswerLog();

    function getIntegralRank();

    function getMemberLearnLog();

    function getMemberIntegralLogs();

    function changeMemberInfo(array $update): Members;
}