<?php

Route::group([], function ($router) {

    /**
     * 登录模块 The login module
     */
    $router->group(['namespace' => 'Auth', 'middleware' => 'language'], function ($router) {
        $router->get('login', 'LoginController@showLoginForm')->name('login');
        $router->post('login', 'LoginController@login')->name('admin.login');
        $router->post('logout', 'LoginController@logout')->name('admin.logout');
    });

    /**
     * 核心模块 The core module
     */
    $router->group(['namespace' => 'Admin'], function ($router) {

        $router->group(['middleware' => ['auth', 'check.permission', 'language']], function ($router) {

            $router->post('/setting/admin', 'UserController@setting')->name('admin.setting.adminer');

            $router->get('/', 'HomeController@index')->name('admin.home');

            // 权限
            $router->resource('permission', 'PermissionController');
            // 角色
            $router->resource('role', 'RoleController');
            // 用户
            $router->resource('user', 'UserController');
            // 菜单
            $router->get('menu/clear', 'MenuController@cacheClear');
            $router->resource('menu', 'MenuController');
            $router->get('setting/{lang}', 'SettingController@language');

            // 视图操作分之测试s
            $router->resource('view', 'ViewsController');

            // 期题管理
            $router->resource('cycle', 'CycleController');
            // 期题达标人员
            $router->get('cycle/rate/member/{cid}', 'CycleController@rate')->name('cycle.rate.member');

            // 期题题目管理
            $router->resource('question', 'QuestionController');
            // 题目迁移至专项答题
            $router->post('/question/change/special', 'QuestionController@changeSpecial')
                ->name('question.change.special');

            // 答案管理
            $router->resource('options', 'QuestionOptionsController');

            // 会员管理
            $router->resource('members', 'MembersController');
            // 会员答题记录
            $router->get('/members/logs/{id}', 'MembersController@logs')->name('members.logs');

            // 学习知识点管理
            $router->resource('learn', 'LearnController');

            // 专项答题管理
            $router->resource('special', 'SpecialController');

            //新闻类别
            $router->resource('news-type', 'NewsTypeController');

            //新闻详情
            $router->resource('news-world', 'NewsController');
        });

    });
});
