<?php

namespace App\Admin\Actions\Post;

use App\Models\Customer;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;

class WithdrawAll extends BatchAction
{
    public $name = '批量审核';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            if($model->status==0){

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
            }
        }

        return $this->response()->success('批量审核通过')->refresh();
    }

}