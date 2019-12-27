<?php


namespace App\Services\Wechat;


interface LearnService
{
    function getLearnLists();

    function findLearn(int $id);

}