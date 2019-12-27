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

        //用户相关
        $router->group(['prefix' => 'member'], function ($router) {
            $router->get('/', 'MemberController@info');
            $router->get('/answer/logs', 'MemberController@answerLists');
        });

        //题目相关
        $router->group(['prefix' => 'cycle'], function ($router) {
            //题期列表
            $router->get('/lists', 'CycleController@lists');
            //单期题目列表
            $router->get('/question/{id}', 'CycleController@question');
            //下一组题目列表
            $router->get('/next/question', 'CycleController@questionNext');
            //提交答卷
            $router->post('/question', 'CycleController@quetionSubmit');
        });

        //知识点相关
        $router->group(['prefix' => 'learn'], function ($router) {
            //知识点列表
            $router->get('/', 'LearnController@lists');
            //知识点详情
            $router->get('/{id}', 'LearnController@findLearn')->where(['id' => '[0-9]+']);
        });

    });

});
