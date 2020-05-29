<?php

namespace App\Http\Controllers\Wechat;

use App\Handlers\WechatConfigHandler;
use App\Models\Article;
use App\Models\Category;
use App\Models\Config;
use App\Models\Customer;
use App\Models\Fraud;
use App\Models\Lighthouse;
use App\Models\MobileOrder;
use App\Models\Order;
use App\Models\Safeguard;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

class IndexController extends Controller
{
    protected $wechat;

    //获取姓氏
    public function getXingList()
    {
        $arrXing = array('赵', '钱', '孙', '李', '周', '吴', '郑', '王', '冯', '陈', '褚', '卫', '蒋', '沈', '韩', '杨', '朱', '秦', '尤', '许', '何', '吕', '施', '张', '孔', '曹', '严', '华', '金', '魏', '陶', '姜', '戚', '谢', '邹',
            '喻', '柏', '水', '窦', '章', '云', '苏', '潘', '葛', '奚', '范', '彭', '郎', '鲁', '韦', '昌', '马', '苗', '凤', '花', '方', '任', '袁', '柳', '鲍', '史', '唐', '费', '薛', '雷', '贺', '倪', '汤', '滕', '殷', '罗',
            '毕', '郝', '安', '常', '傅', '卞', '齐', '元', '顾', '孟', '平', '黄', '穆', '萧', '尹', '姚', '邵', '湛', '汪', '祁', '毛', '狄', '米', '伏', '成', '戴', '谈', '宋', '茅', '庞', '熊', '纪', '舒', '屈', '项', '祝',
            '董', '梁', '杜', '阮', '蓝', '闵', '季', '贾', '路', '娄', '江', '童', '颜', '郭', '梅', '盛', '林', '钟', '徐', '邱', '骆', '高', '夏', '蔡', '田', '樊', '胡', '凌', '霍', '虞', '万', '支', '柯', '管', '卢', '莫',
            '柯', '房', '裘', '缪', '解', '应', '宗', '丁', '宣', '邓', '单', '杭', '洪', '包', '诸', '左', '石', '崔', '吉', '龚', '程', '嵇', '邢', '裴', '陆', '荣', '翁', '荀', '于', '惠', '甄', '曲', '封', '储', '仲', '伊',
            '宁', '仇', '甘', '武', '符', '刘', '景', '詹', '龙', '叶', '幸', '司', '黎', '溥', '印', '怀', '蒲', '邰', '从', '索', '赖', '卓', '屠', '池', '乔', '胥', '闻', '莘', '党', '翟', '谭', '贡', '劳', '逄', '姬', '申',
            '扶', '堵', '冉', '宰', '雍', '桑', '寿', '通', '燕', '浦', '尚', '农', '温', '别', '庄', '晏', '柴', '瞿', '阎', '连', '习', '容', '向', '古', '易', '廖', '庾', '终', '步', '都', '耿', '满', '弘', '匡', '国', '文',
            '寇', '广', '禄', '阙', '东', '欧', '利', '师', '巩', '聂', '关', '荆', '司马', '上官', '欧阳', '夏侯', '诸葛', '闻人', '东方', '赫连', '皇甫', '尉迟', '公羊', '澹台', '公冶', '宗政', '濮阳', '淳于', '单于', '太叔',
            '申屠', '公孙', '仲孙', '轩辕', '令狐', '徐离', '宇文', '长孙', '慕容', '司徒', '司空');
        return $arrXing;

    }

    //获取名字
    public function getMingList()
    {
        $arrMing = array('先生', '女士');
        return $arrMing;
    }

    //获取时间
    public function getTimeList()
    {
        $arrMit = [];
        $arrHour = [];
        for ($i = 1; $i < 60; $i++) {
            $arrMit[$i] = $i . '分钟前';
        }

        for ($ii = 1; $ii < 24; $ii++) {
            $arrHour[$ii] = $ii . '小时前';
        }
        $arrTime = array_merge($arrMit, $arrHour);

        return $arrTime;
    }

    public function getTypeList()
    {
        $arrType = array('购买', '私问');
        return $arrType;
    }

    public function getInfoList()
    {
        $safeguards = Safeguard::all()->pluck('name')->toArray();

//        //函数将一个项目推到数组的开头
//        $safeguards = array_prepend($safeguards, '私问');
//
//        // 随机打乱一个数组
//        shuffle($safeguards);

        return $safeguards;
    }

    public function __construct(WechatConfigHandler $wechat)
    {
        $this->wechat = $wechat;
    }

    public function index()
    {
        $configs = Config::all()->toArray();

        $frauds = Fraud::orderby('sort_order')->get();

        //咨询动态
        $arrXing = $this->getXingList();
        $numbXing = count($arrXing);
        $arrMing = $this->getMingList();
        $numbMing = count($arrMing);
        $arrType = $this->getTypeList();
        $numbType = count($arrType);
        $arrTime = $this->getTimeList();
        $numbTime = count($arrTime);
        $arrInfo = $this->getInfoList();
        $numbInfo = count($arrInfo);

        $info = [];
        for ($i = 1; $i < 10; $i++) {
            $Xing = $arrXing[mt_rand(0, $numbXing - 1)];
            $Ming = $arrMing[mt_rand(0, $numbMing - 1)];
            $Type = $arrType[mt_rand(0, $numbType - 1)];
            $Time = $arrTime[mt_rand(0, $numbTime - 1)];
            $Info = $arrInfo[mt_rand(0, $numbInfo - 1)];
            if ($Type=="私问"){
                $info[$i]['type']=$Type;
                $info[$i]['name']=$Xing.$Ming.'发起私问';
                $info[$i]['time']=$Time;
            }else{
                $info[$i]['type']=$Type;
                $info[$i]['name']=$Xing.$Ming.'购买'.$Info;
                $info[$i]['time']=$Time;
            }
        }

        return $this->array(['config' => $configs, 'frauds' => $frauds, 'info' => $info]);
    }

    public function safeguards()
    {
        $safeguards = Safeguard::orderby('sort_order')->get()->toArray();

        return $this->array($safeguards);
    }

    public function safeguard($id)
    {
        $safeguard = Safeguard::find($id)->toArray();
        return $this->array($safeguard);
    }

    public function categories()
    {
        $categories = Category::with(['articles' => function ($query) {
            $query->orderby('sort_order')->get();
        }])->orderby('sort_order')->get()->toArray();

        return $this->array($categories);
    }

    public function articles(Request $request)
    {
        $category_id = $request->category_id;
        $articles = Article::where('category_id', $category_id)->paginate($request->total);

        $page = isset($page) ? $request['page'] : 1;
        $articles = $articles->appends(array(
            'page' => $page,
        ));

        return $this->object($articles);
    }

    public function article($id)
    {
        $article = Article::find($id)->toArray();

        return $this->array($article);
    }

    public function lighthouses(Request $request)
    {
        $lighthouses = Lighthouse::where('is_show', true)->orderby('sort_order')->paginate($request->total);

        $page = isset($page) ? $request['page'] : 1;
        $lighthouses = $lighthouses->appends(array(
            'page' => $page,
        ));

        return $this->object($lighthouses);
    }

    public function lighthouse(Request $request)
    {
        try {
            $messages = [
                'name.required' => '平台名称不能为空!',
                'url.required' => '平台网址不能为空!',
                'phone.required' => '联系方式不能为空!',
            ];
            $rules = [
                'phone' => 'required',
                'url' => 'required',
                'name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();

                $this->error(500, $error);
            }
            Lighthouse::create($request->all());

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            $this->error(500, $exception->getMessage());
        }

        return $this->null();
    }

    public function customer()
    {
        $customer_id = session('wechat.customer.id');

        $customer = Customer::with('children')->find($customer_id)->toArray();

        return $this->array($customer);
    }

    public function group(Request $request)
    {
        $customer_id = session('wechat.customer.id');

        $customers = Customer::where('parent_id', $customer_id)->paginate($request->total);

        $page = isset($page) ? $request['page'] : 1;
        $customers = $customers->appends(array(
            'page' => $page,
        ));

        return $this->object($customers);
    }

    public function do_withdraw(Request $request)
    {
        try {
            $messages = [
                'money.required' => '提现金额不能为空!',
                'image.required' => '图片不能为空!',
            ];
            $rules = [
                'money' => 'required',
                'image' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $error = $validator->errors()->first();

                $this->error(500, $error);
            }

            $request->offsetSet('customer_id', session('wechat.customer.id'));

            Withdraw::create($request->all());

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            $this->error(500, $exception->getMessage());
        }

        return $this->null();
    }

    public function withdraw(Request $request)
    {
        $customer_id = session('wechat.customer.id');

        $list = Activity::where(['subject_id' => $customer_id, 'log_name' => 'withdraw'])->paginate($request->total);

        $page = isset($page) ? $request['page'] : 1;
        $list = $list->appends(array(
            'page' => $page,
        ));

        return $this->object($list);
    }


    public function money(Request $request)
    {
        $customer_id = session('wechat.customer.id');

        $list = Activity::where(['subject_id' => $customer_id, 'log_name' => 'money'])->paginate($request->total);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]['customer'] = Customer::find($value['causer_id']);
            }
        }

        $page = isset($page) ? $request['page'] : 1;
        $list = $list->appends(array(
            'page' => $page,
        ));

        return $this->object($list);
    }

    public function code(Request $request)
    {
        $code = $request->code;
        $customer_id = session('wechat.customer.id');
        $customer = Customer::find($customer_id);

        $parent = Customer::where('code', $code)->first();
        if (!empty($parent)) {
            $customer->parent_id = $parent->id;
            $customer->save();
            return $this->null();
        } else {
            return $this->error(500, '邀请码错误！');
        }
    }

    public function order(Request $request)
    {

        //多条件查找
        $where = function ($query) use ($request) {
            $query->where('customer_id', session('wechat.customer.id'));

            switch ($request->status) {
                case '':
                    break;
                case '1':
                    $query->where('status', 1);
                    break;
                case '2':
                    $query->where('status', 2);
                    break;
                case '3':
                    $query->where('status', 3);
                    break;
            }

        };
        $orders = Order::with('safeguard')->where($where)->paginate($request->total);

        $page = isset($page) ? $request['page'] : 1;
        $orders = $orders->appends(array(
            'page' => $page,
            'status' => $request->status,
        ));

        return $this->object($orders);
    }


    public function pay(Request $request)
    {
        if (!session('wechat.customer')) {
            $openid = $request->openid;
            $customer = Customer::where('openid', $openid)->first();
            session(['wechat.customer' => $customer]);
        }
        $safeguard_id = $request->safeguard_id;
        $order_sn = date('YmdHms', time()) . '_' . session('wechat.customer.id');
        $safeguard = Safeguard::find($safeguard_id);

        $app = $this->wechat->pay(1);
        $title = $safeguard->name;
        $total_price = $safeguard->total_price;


        $order_config = [
            'body' => $title,
            'out_trade_no' => $order_sn,
            'total_fee' => $total_price * 100,
            //'spbill_create_ip' => '', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/api/wechat/paid', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => session('wechat.customer.openid'),
        ];

        //生成预支付生成订单
        $result = $app->order->unify($order_config);

        //return $result;
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {

            $order = Order::create([
                'customer_id' => session('wechat.customer.id'),
                'order_sn' => $order_sn,
                'safeguard_id' => $safeguard_id,
                'total_price' => $total_price,
            ]);


            $prepayId = $result['prepay_id'];

            $config = $app->jssdk->sdkConfig($prepayId);
            return response()->json($config);
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function paid(Request $request)
    {
        $app = $this->wechat->pay(1);
        $response = $app->handlePaidNotify(function ($message, $fail) use ($request) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where('order_sn', $message['out_trade_no'])->first();

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->pay_time = date('Y-m-d H:i:s', time()); // 更新支付时间为当前时间

                    $customer_id = $order->customer_id;
                    $customer = Customer::find($customer_id);

                    activity()->inLog('buy')
                        ->performedOn($customer)
                        ->causedBy($order)
                        ->withProperties(['type' => 0, 'money' => $order->total_price])
                        ->log('购买服务');

                    if ($customer->parent_id) {
                        $parent = Customer::find($customer->parent_id);
                        $money = $order->total_price * config('rate');
                        $parent->money += $money;
                        activity()->inLog('money')
                            ->performedOn($parent)
                            ->causedBy($customer)
                            ->withProperties(['type' => 1, 'money' => $money])
                            ->log('推广佣金');
                    }

                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }


    public function pay_mobile(Request $request)
    {
        if (!session('wechat.customer')) {
            $openid = $request->openid;
            $customer = Customer::where('openid', $openid)->first();
            session(['wechat.customer' => $customer]);
        }
        $order_sn = date('YmdHms', time()) . '_' . session('wechat.customer.id');

        $configs = Config::all()->toArray();

        $app = $this->wechat->pay(1);
        $title = '电话咨询';
        $total_price = $configs->server_price;

        $order_config = [
            'body' => $title,
            'out_trade_no' => $order_sn,
            'total_fee' => $total_price * 100,
            //'spbill_create_ip' => '', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/api/wechat/paid_mobile', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => session('wechat.customer.openid'),
        ];
        //生成预支付生成订单
        $result = $app->order->unify($order_config);
        //return $result;
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {

            MobileOrder::create([
                'customer_id' => session('wechat.customer.id'),
                'order_sn' => $order_sn,
                'total_price' => $total_price,
            ]);
            $prepayId = $result['prepay_id'];
            $config = $app->jssdk->sdkConfig($prepayId);
            return response()->json($config);
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     */
    public function paid_mobile(Request $request)
    {
        $app = $this->wechat->pay(1);
        $response = $app->handlePaidNotify(function ($message, $fail) use ($request) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = MobileOrder::where('order_sn', $message['out_trade_no'])->first();

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->pay_time = date('Y-m-d H:i:s', time()); // 更新支付时间为当前时间

                    $customer_id = $order->customer_id;
                    $customer = Customer::find($customer_id);

                    activity()->inLog('buy')
                        ->performedOn($customer)
                        ->causedBy($order)
                        ->withProperties(['type' => 0, 'money' => $order->total_price])
                        ->log('购买咨询');

                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }

    public function refund($order_id)
    {
        $order = Order::find($order_id);
        $order->status = 3;
        $order->save();

        return $this->null();
    }


    public function upload_img(Request $request)
    {

        if ($request->hasFile('file') and $request->file('file')->isValid()) {

            //文件大小判断$filePath
            $max_size = 1024 * 1024 * 3;
            $size = $request->file('file')->getClientSize();
            if ($size > $max_size) {
                return $this->error(500, '文件大小不能超过3M！');
            }

            $path = $request->file->store('upload', 'public');

            return $this->array(['image' => '/' . $path, 'image_url' => '/' . $path]);

        }
    }
}
