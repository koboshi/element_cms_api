<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


//路由定义
Route::group('menu', function () {
   Route::rule('list', 'admin.menu/list');
   Route::rule('tree', 'admin.menu/tree');
});

Route::group('user', function() {
    Route::rule('list', 'admin.user/list');
});

Route::group(function () {
    Route::rule('login', 'admin.auth/login');
    Route::rule('logout', 'admin.auth/logout');
    Route::rule('chg_pwd', 'admin.auth/changePassword');
});