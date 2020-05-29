<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//微信小程序 'middleware' => ['wechat.oauth','wechat'],
Route::group(['middleware' => [],'namespace' => 'Wechat', 'prefix' => 'wechat', 'as' => 'wechat.'], function () {

    //授权
    Route::post('/auth', 'WechatController@auth');
    //首页
    Route::get('index', 'IndexController@index');
    //维权入口
    Route::get('safeguards', 'IndexController@safeguards');
    Route::get('safeguard/{id}', 'IndexController@safeguard');
    //法律法规&维权指南&服务范围
    Route::get('categories', 'IndexController@categories');
    Route::get('articles', 'IndexController@articles');
    Route::get('article/{id}', 'IndexController@article');
    //曝光台
    Route::get('lighthouses', 'IndexController@lighthouses');
    Route::post('lighthouse', 'IndexController@lighthouse');

    //用户信息
    Route::get('customer', 'IndexController@customer');
    //我的团队
    Route::get('group', 'IndexController@group');

    //提现记录
    Route::get('withdraw', 'IndexController@withdraw');
    Route::post('do_withdraw', 'IndexController@do_withdraw');
    //佣金明细
    Route::get('money', 'IndexController@money');

    //邀请用户
    Route::get('code', 'IndexController@code');

    //我的订单
    Route::get('order', 'IndexController@order');
    //付款
    Route::post('pay', 'IndexController@pay');
    //取消订单
    Route::post('refund/{id}', 'IndexController@refund');

    //付款咨询
    Route::post('pay_mobile', 'IndexController@pay_mobile');


    //上传图片
    Route::post('upload_img', 'IndexController@upload_img');



    //微信公众号接口
    Route::any('/easywechat', 'WechatController@serve');
    Route::any('/oauth_callback', 'WechatController@oauth_callback');

    //付款回调
    Route::any('paid', 'IndexController@paid');
    Route::any('paid_mobile', 'IndexController@paid_mobile');

});
