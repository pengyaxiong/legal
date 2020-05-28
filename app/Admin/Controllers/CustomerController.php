<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('id', __('Id'));
        $grid->column('parent_id', __('Parent id'));
        $grid->column('openid', __('Openid'))->copyable();
        $grid->column('nickname', __('Nickname'))->copyable();
        $grid->column('headimgurl', __('Headimgurl'))->image();
        $grid->column('sex', __('Sex'))->using([
            1 => '男',
            2 => '女',
            0 => '其它',
        ], '未知')->dot([
            1 => 'primary',
            2 => 'danger',
            0 => 'success',
        ], 'warning');
        $grid->column('language', __('Language'));
        $grid->column('tel', __('Tel'));
        $grid->column('country', __('Country'));
        $grid->column('province', __('Province'));
        $grid->column('city', __('City'));
        $grid->column('email', __('Email'));
        $grid->column('code', __('Code'))->qrcode();
        $grid->column('money', __('Money'))->editable();
        $grid->column('withdraw_money', __('Withdraw money'));
        $grid->column('dis_money', __('Dis money'))->editable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        //禁用创建按钮
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });

        $grid->filter(function ($filter) {
            $filter->like('nickname', '微信昵称');
            $filter->like('openid', 'OpenId');
            $filter->equal('parent_id', __('Parent id'));
            $status_text = [
                1 => '男',
                2 => '女',
                0 => '其它'
            ];
            $filter->equal('sex', __('Sex'))->select($status_text);
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
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('parent_id', __('Parent id'));
        $show->field('openid', __('Openid'));
        $show->field('sex', __('Sex'));
        $show->field('language', __('Language'));
        $show->field('nickname', __('Nickname'));
        $show->field('headimgurl', __('Headimgurl'));
        $show->field('tel', __('Tel'));
        $show->field('country', __('Country'));
        $show->field('province', __('Province'));
        $show->field('city', __('City'));
        $show->field('email', __('Email'));
        $show->field('money', __('Money'));
        $show->field('code', __('Code'));
        $show->field('withdraw_money', __('Withdraw money'));
        $show->field('dis_money', __('Dis money'));
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
        $form = new Form(new Customer());

        $form->number('parent_id', __('Parent id'));
        $form->text('openid', __('Openid'));
        $form->switch('sex', __('Sex'));
        $form->text('language', __('Language'));
        $form->text('nickname', __('Nickname'));
        $form->text('headimgurl', __('Headimgurl'));
        $form->text('tel', __('Tel'));
        $form->text('country', __('Country'));
        $form->textarea('province', __('Province'));
        $form->textarea('city', __('City'));
        $form->email('email', __('Email'));
        $form->decimal('money', __('Money'))->default(0.00);
        $form->text('code', __('Code'));
        $form->decimal('withdraw_money', __('Withdraw money'));
        $form->decimal('dis_money', __('Dis money'));


        //保存后回调
        $form->saved(function (Form $form) {


        });

        //保存前回调
        $form->saving(function (Form $form) {
            $customer = $form->model();

            if ($form->money) {
                $money = $form->money - $customer->money;
                activity()->inLog('system_money')
                    ->performedOn($customer)
                    ->causedBy(auth('admin')->user())
                    ->withProperties(['type' => 1, 'money' => $money])
                    ->log('系统操作佣金');
            }

            if ($form->dis_money) {
                $dis_money = $form->dis_money - $customer->dis_money;
                activity()->inLog('system_dis_money')
                    ->performedOn($customer)
                    ->causedBy(auth('admin')->user())
                    ->withProperties(['type' => 1, 'money' => $dis_money])
                    ->log('系统操作冻结佣金');
            }
        });

        return $form;
    }
}
