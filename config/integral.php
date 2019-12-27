<?php
return [
    'num' => env('MEMBER_INTRGRAL', 0),

    'login' => [
        'today_count_num' => env('TODAY_LOGIN_NUM', 0), //单日登录可得积分
    ],
    //题库积分配置
    'question_bank' => [
        'today_count_num' => env('TODAY_BANK_NUM', 0),  //单日题库最多可获得积分
        'today_bank' => env('TODAY_BANK', 0) //单次答题最高积分
    ],

    //每日阅读知识点获取积分的次数
    'learn' => [
        'today_read_num' => env('TODAY_READ_NUM', 0), //单日阅读可获取总积分
        'today_read' => env('TODAY_READ'), //单日单次阅读可获得的积分数量
    ]
];