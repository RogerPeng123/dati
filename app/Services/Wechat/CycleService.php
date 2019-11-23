<?php


namespace App\Services\Wechat;


interface CycleService
{
    function cycleLists();

    function cycleQuestion(int $id);

    function cycleSubmit(array $params, string $token);

}