<?php
return [
    'num' => env('MEMBER_INTRGRAL', 0),

    //每日阅读知识点获取积分的次数
    'learn' => [
        'today_read_num' => env('TODAY_READ_NUM', 0), //单日阅读可获取总积分
        'today_read' => env('TODAY_READ'), //单日单次阅读可获得的积分数量
    ]
];