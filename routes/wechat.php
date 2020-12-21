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

            //更改手机号码
            $router->post('/change/mobile', 'MemberController@changeMobile');
            //更换密码
            $router->post('/change/password', 'MemberController@changePassword');

            //答题记录
            $router->get('/answer/logs', 'MemberController@answerLists');
            //学习记录
            $router->get('/learn/record', 'MemberController@learnLists');
            //积分排行榜
            $router->get('/integral/rank', 'MemberController@rankIntegral');
            //用户积分记录
            $router->get('/integral/logs', 'MemberController@integralLogs');
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

        //专项答题相关
        $router->group(['prefix' => 'special'], function ($router) {
            //专项答题列表
            $router->get('/lists', 'SpecialController@lists');
            //下一组题目
            $router->get('/next/question', 'SpecialController@questionNext');
        });

    });

    //新闻中心
    $router->group(['prefix' => 'news'], function ($router) {
        //分类列表
        $router->get('/types', 'NewsController@types');
        //新闻列表
        $router->get('/list/{type}', 'NewsController@getNews')->where(['type' => '[0-9]+']);
        //新闻详情
        $router->get('/detail/{id}', 'NewsController@findNews')->where(['id' => '[0-9]+']);
    });
});
