<?php


namespace App\Services\Wechat;


interface CycleService
{
    function cycleLists($user);

    function cycleQuestion(int $id);

    function cycleQuestionNext();

    function cycleSubmit(array $params);

    function getCycleSpecialList();

    function getCycleSpecialNextList();
}