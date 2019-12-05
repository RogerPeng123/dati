<?php


namespace App\Services\Wechat;


interface CycleService
{
    function cycleLists($user);

    function cycleQuestion(int $id);

    function cycleSubmit(array $params, string $token);

}