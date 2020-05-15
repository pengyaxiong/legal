<?php

namespace App\Http\Controllers\Wechat;

use App\Handlers\WechatConfigHandler;
use App\User;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    protected $wechat;

    public function __construct(WechatConfigHandler $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * @param $account
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        $app = $this->wechat->app();


        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']) {
                        //关注事件
                        case 'subscribe':
                            return '欢迎关注~';
                            break;
                        //取消关注
                        case 'unsubscribe':
                            return '我还会回来的~';
                            break;

                        //点击事件
                        case 'CLICK':
                            switch ($message['EventKey']) {
                                case 'recommend':
                                    return 12312;
                                    break;
                            }
                            break;
                    }
                    break;
                case 'text':
                    switch ($message['Content']) {
                        case '精选':
                        case '推荐':
                        case '精选推荐':
                        case 'recommend':
                            return 12321;
                            break;

                        default:
                            return $this->default_msg();
                    }
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    switch ($message['Recognition']) {
                        case '精选。':
                        case '推荐。':
                        case '精选推荐。':
                            return 123123;
                            break;

                        default:
                            return '您说的是:' . $message['Recognition'] . '?';
                    }
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        $response = $app->server->serve();
        return $response;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oauth_callback()
    {
        $app = $this->wechat->app();
        $user = $app->oauth->user();
        session(['wechat.oauth_user' => $user->toArray()]);
        //不管在哪个页面检测用户登录状态，都要写入session值：target_url
        $targetUrl = session()->has('target_url') ? session('target_url') : '/';
        //header('location:'. $targetUrl);
        return redirect()->to($targetUrl);
    }

    /**
     * 默认消息
     * @return string
     */
    function default_msg()
    {
        return '有趣的问题~';
    }

    //获取GET请求
    function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }


    public function auth(Request $request)
    {
        $mini = $this->wechat->mini_config();

        //声明CODE，获取小程序传过来的CODE
        $code = $request->code;
        //配置appid
        $appid = $mini->app_id;
        //配置appscret
        $secret = $mini->secret;
        //api接口
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

        $str = json_decode($this->httpGet($api), true);

        if (!session('wechat.user')) {

            $openid = $str['openid'];

            $user = User::where('openid', $openid)->first();

            if ($user) {
                $user->update([
                    'openid' => $openid,
                    'headimgurl' => $request->headimgurl,
                    'nickname' => $request->nickname,
                ]);

            } else {
                $user = User::create([
                    'openid' => $openid,
                    'headimgurl' => $request->headimgurl,
                    'nickname' => $request->nickname,
                ]);

            }

            session(['wechat.user' => $user]);
        }

        return $this->array($str, '授权成功');
    }
}