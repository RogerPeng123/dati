<?php


namespace App\Services\Wechat;


interface MemberService
{
    function registerMember(array $params);

    function memberLogin(array $params);

    function memberInfo();

    function getMemberAnswerLog();

    function getIntegralRank();

    function getMemberLearnLog();
}