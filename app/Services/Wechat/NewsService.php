<?php


namespace App\Services\Wechat;


interface NewsService
{
    function getTypes();

    function getNews(int $type);

    function findNews(int $id);
}