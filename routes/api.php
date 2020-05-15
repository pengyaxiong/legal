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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//微信小程序 'middleware' => ['wechat.oauth','wechat'],
Route::group(['namespace' =>'Wechat' ,'prefix' => 'wechat', 'as' => 'wechat.'], function () {

    Route::get('/', function () {
        return config('aid');
    });

    //授权
    Route::post('/auth', 'WechatController@auth');



    //微信公众号接口
    Route::any('/easywechat', 'WechatController@serve');
    Route::any('/oauth_callback', 'WechatController@oauth_callback');

    //付款回调
//    Route::any('paid', 'OrderController@paid');

});
