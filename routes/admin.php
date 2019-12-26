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

            // 期题题目管理
            $router->resource('question', 'QuestionController');

            //答案管理
            $router->resource('options', 'QuestionOptionsController');

            //会员管理
            $router->resource('members', 'MembersController');
            //会员答题记录
            $router->get('/members/logs/{id}', 'MembersController@logs')->name('members.logs');
        });

    });
});
