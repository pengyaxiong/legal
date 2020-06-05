<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Safeguard;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('order_sn', __('Order sn'));
        $grid->column('customer.nickname', __('Customer id'));
        $grid->column('safeguard.name', __('Safeguard id'));

        $grid->column('status', __('Status'))->replace([
            1 => '待支付',
            2 => '已完成',
            3 => '已取消',
        ])->label([
            1 => 'default',
            2 => 'success',
            3 => 'warning',
        ]);
        $grid->column('pay_type', __('Pay type'))->using([
            1 => '微信支付',
        ], '未知')->dot([
            1 => 'primary',
        ], 'warning');

        $grid->column('total_price', __('Total price'));

        $grid->column('solution', __('Solution'))->downloadable();

        $grid->column('pay_time', __('Pay time'))->hide();
        $grid->column('finish_time', __('Finish time'))->hide();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->filter(function ($filter) {
            $filter->equal('order_sn', __('Order sn'));
            $status_text = [
                1 => '待支付',
                2 => '已完成',
                3 => '已取消',
            ];
            $filter->equal('status', __('Status'))->select($status_text);
            $filter->between('created_at', __('Created at'))->date();
        });

        //禁用创建按钮
            $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableView();
           // $actions->disableEdit();
            $actions->disableDelete();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_sn', __('Order sn'));
        $show->field('customer_id', __('Customer id'));
        $show->field('safeguard_id', __('Safeguard id'));
        $show->field('status', __('Status'));
        $show->field('pay_type', __('Pay type'));
        $show->field('total_price', __('Total price'));
        $show->field('pay_time', __('Pay time'));
        $show->field('finish_time', __('Finish time'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());

        $form->text('order_sn', __('Order sn'));

        $customers = Customer::all()->toArray();
        $select_array = array_column($customers, 'nickname', 'id');
        $form->select('customer_id', __('Customer id'))->options($select_array)->rules('required');

        $safeguards = Safeguard::all()->toArray();
        $select_array = array_column($safeguards, 'name', 'id');
        $form->select('safeguard_id', __('Safeguard id'))->options($select_array)->rules('required');

        $form->select('status', __('Status'))->options([1 => '待支付', 2 => '已完成', 3 => '已取消'])->default(1);

        $states = [
            'on' => ['value' => 1, 'text' => '微信支付', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '其他', 'color' => 'danger'],
        ];

        $form->switch('pay_type', __('Pay type'))->states($states)->default(1);

        $form->decimal('total_price', __('Total price'))->rules('required');

		$form->text('name', __('称呼'));
		$form->text('tel', __('手机号'));
		$form->text('platform', __('被骗平台'));
		$form->text('money', __('受骗金额'));
		$form->textarea('pass', __('受骗经过'));
		
        // 增加一个下载按钮，可点击下载
        $form->file('solution', __('Solution'))->downloadable();

        $form->datetime('pay_time', __('Pay time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('finish_time', __('Finish time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
