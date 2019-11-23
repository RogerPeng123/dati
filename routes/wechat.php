<?php

Route::group(['namespace' => 'Wechat'], function ($router) {

    $router->group(['namespace' => 'Handle'], function ($router) {
        //注册
        $router->post('/register', RegisterHandle::class);
        //登录
        $router->post('/login', LoginHandle::class);
        //退出
        $router->post('/logout', LogoutHandle::class);
    });

    //登录成功，也就是携带token才能进行的操作
    $router->group(['middleware' => 'api.login.check'], function ($router) {
        //题期列表
        $router->get('/cycle/lists', 'CycleController@lists');

        //单期题目列表
        $router->get('/cycle/question/{id}', 'CycleController@question');

        //提交答卷
        $router->post('/cycle/question', 'CycleController@quetionSubmit');
    });


    $router->get('/test', function () {

        $array = [
            [
                "q_id" => 3, "answer" => 1
            ],
            [
                "q_id" => 4, "answer" => 8
            ],
            [
                "q_id" => 5, "answer" => 1
            ]
        ];

        return json_encode($array);
    });

});
