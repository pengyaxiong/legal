<?php

namespace App\Http\Controllers\Wechat;

use App\Models\Article;
use App\Models\Category;
use App\Models\Config;
use App\Models\Customer;
use App\Models\Lighthouse;
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
    public function index()
    {
        $configs = Config::all()->toArray();

        return $this->array($configs);
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

    public function article($id)
    {
        $article = Article::find($id)->toArray();

        return $this->array($article);
    }

    public function lighthouses()
    {
        $lighthouses = Lighthouse::where('is_show', true)->orderby('sort_order')->get()->toArray();

        return $this->array($lighthouses);
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

    public function withdraw()
    {
        $customer_id = session('wechat.customer.id');

        $list = Activity::where(['subject_id' => $customer_id, 'log_name' => 'withdraw'])->get()->toarray();

        return $this->array($list);
    }


    public function money()
    {
        $customer_id = session('wechat.customer.id');

        $list = Activity::where(['subject_id' => $customer_id, 'log_name' => 'money'])->get()->toarray();

        return $this->array($list);
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
}
