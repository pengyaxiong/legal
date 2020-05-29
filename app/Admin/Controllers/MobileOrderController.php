<?php

namespace App\Admin\Controllers;

use App\Models\MobileOrder;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MobileOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '咨询电话订单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MobileOrder());

        $grid->column('id', __('Id'));
        $grid->column('order_sn', __('Order sn'));
        $grid->column('customer.nickname', __('Customer id'));
        $grid->column('total_price', __('Total price'));
        $grid->column('pay_time', __('Pay time'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        //禁用创建按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(MobileOrder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_sn', __('Order sn'));
        $show->field('customer_id', __('Customer id'));
        $show->field('total_price', __('Total price'));
        $show->field('pay_time', __('Pay time'));
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
        $form = new Form(new MobileOrder());

        $form->text('order_sn', __('Order sn'));
        $form->number('customer_id', __('Customer id'));
        $form->decimal('total_price', __('Total price'));
        $form->datetime('pay_time', __('Pay time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
