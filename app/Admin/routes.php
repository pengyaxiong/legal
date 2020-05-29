<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');


    //首页-轮播图&通知&咨询动态&关于我们
    $router->resource('configs', "ConfigController");
    //法律法规&维权指南&服务范围
    $router->resource('articles', 'ArticleController');
    $router->resource('categories', 'CategoryController');
    //骗局盘点
    $router->resource('frauds', 'FraudController');
    //维权入口
    $router->resource('safeguards', 'SafeguardController');
    //曝光台
    $router->resource('lighthouses', 'LighthouseController');

    //会员管理
    $router->resource('customers', 'CustomerController');

    //提现申请
    $router->resource('withdraws', 'WithdrawController');
    //订单管理
    $router->resource('orders', 'OrderController');
    //咨询电话订单
    $router->resource('mobile-orders', 'MobileOrderController');
});
