<?php


namespace App\Toolkit;


final class ActionToolkit
{
    /**
     * 获取当前控制器指向的方法名
     * Author: roger peng
     * Time: 2019/11/20 20:48
     * @return false|string
     */
    public static function getActionName()
    {
        $actionNameItem = request()->route()->getActionName();

        return substr($actionNameItem, strpos($actionNameItem, '@') + 1);
    }

    public static function setApiToken($key): string
    {
        return md5($key . '[\u5c0f\u6cfd\u739b\u5229\u4e9a]' . time());
    }

}