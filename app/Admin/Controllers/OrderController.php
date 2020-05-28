<?php

namespace App\Admin\Controllers;

use App\Models\Order;
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
        $grid->column('customer_id', __('Customer id'));
        $grid->column('safeguard_id', __('Safeguard id'));
        $grid->column('status', __('Status'));
        $grid->column('pay_type', __('Pay type'));
        $grid->column('total_price', __('Total price'));
        $grid->column('pay_time', __('Pay time'));
        $grid->column('finish_time', __('Finish time'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        //禁用创建按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
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
        $form->number('customer_id', __('Customer id'));
        $form->number('safeguard_id', __('Safeguard id'));
        $form->switch('status', __('Status'))->default(1);
        $form->switch('pay_type', __('Pay type'))->default(1);
        $form->decimal('total_price', __('Total price'));
        $form->datetime('pay_time', __('Pay time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('finish_time', __('Finish time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
