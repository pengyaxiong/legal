<?php

namespace App\Admin\Actions\Post;

use App\Models\Customer;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends RowAction
{
    public $name = '审核通过';

    public function handle(Model $model)
    {
        // $model ...
        if ($model->status==1){
            return $this->response()->error('该申请已经审核通过！')->refresh();
        }

        $customer=Customer::find($model->customer_id);
        $customer->money-=$model->money;
        $customer->withdraw_money+=$model->money;
        $customer->save();

        activity()->inLog('withdraw')
            ->performedOn($customer)
            ->causedBy($model)
            ->withProperties(['type' => 1,'money'=>$model->money])
            ->log('提现');

        $model->status=1;
        $model->save();

        return $this->response()->success('审核通过')->refresh();
    }

}