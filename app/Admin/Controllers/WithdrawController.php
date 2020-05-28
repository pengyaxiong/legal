<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\WithdrawAll;
use App\Models\Withdraw;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WithdrawController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '提现申请';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Withdraw());

        $grid->column('id', __('Id'));
        $grid->column('customer.nickname', __('Customer id'));
        $grid->column('customer.money', __('佣金'));
        $grid->column('customer.withdraw_money', __('已提现佣金'));
        $grid->column('status', __('Status'))->using([
            1 => '审核通过',
            0 => '待审核',
        ], '未知')->dot([
            1 => 'success',
            0 => 'danger',
        ], 'warning');
        $grid->column('money', __('申请金额'));
        $grid->column('image', __('Image'))->image();
        $grid->column('finish_time', __('Finish time'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        //禁用创建按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();

            $actions->add(new \App\Admin\Actions\Post\Withdraw());

        });

        $grid->batchActions(function ($batch) {
            $batch->add(new WithdrawAll());
        });

        $grid->filter(function ($filter) {
            $filter->like('customer.nickname', '微信昵称');
            $filter->like('customer.openid', 'OpenId');
            $status_text = [
                1 => '审核通过',
                0 => '待审核'
            ];
            $filter->equal('status', __('Status'))->select($status_text);
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
        $show = new Show(Withdraw::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('customer_id', __('Customer id'));
        $show->field('status', __('Status'));
        $show->field('money', __('Money'));
        $show->field('finish_time', __('Finish time'));
        $show->field('image', __('Image'));
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
        $form = new Form(new Withdraw());

        $form->number('customer_id', __('Customer id'));
        $form->switch('status', __('Status'))->default(1);
        $form->decimal('money', __('Money'));
        $form->datetime('finish_time', __('Finish time'))->default(date('Y-m-d H:i:s'));
        $form->textarea('image', __('Image'));

        return $form;
    }
}
