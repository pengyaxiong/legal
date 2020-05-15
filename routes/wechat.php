<?php

/**
 *
 */
Route::group(['middleware' => ['guest']], function () {
    Route::post('login', 'Auth\WechatLoginController@login')->name('wechat.login');
    //这里放未登录的api
});

Route::group(['middleware' => ['auth:wechat']], function () {
    //这里放置你的需要登录的 api 路由，如用户资料API、修改资料API...
});