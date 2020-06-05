<?php

namespace App\Admin\Controllers;

use App\Models\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '基本设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config());

        $grid->column('id', __('Id'));
        $grid->column('banner', __('轮播图'))->hide();
        $grid->column('message', __('公告'))->hide();
        $grid->column('image', __('首页横幅'))->hide();
        $grid->column('us_image', __('关于我们图片'))->hide();
        $grid->column('us_description', __('关于我们内容'))->hide();
        $grid->column('phone', __('售后电话'))->editable();
        $grid->column('server_phone', __('咨询电话'))->editable();
        $grid->column('server_price', __('咨询价格'))->editable();
        $grid->column('server_time', __('咨询时间'))->editable();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        //禁用创建按钮
        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableView();
            //  $actions->disableEdit();
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
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('banner', __('Banner'));
        $show->field('message', __('Message'));
        $show->field('image', __('Image'));
        $show->field('us_image', __('Us image'));
        $show->field('us_description', __('Us description'));
        $show->field('phone', __('Phone'));
        $show->field('server_phone', __('Server phone'));
        $show->field('server_price', __('Server price'));
        $show->field('server_time', __('Server time'));
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
        $form = new Form(new Config());

        $form->multipleImage('banner', __('轮播图'))->sortable()->rules('required|image');

        $form->table('message', __('公告'), function ($table) {
            $table->text('desc',__('内容'))->rules('required');
        });

        $form->image('image', __('首页横幅'))->rules('required|image');

        $form->text('phone', __('售后电话'))->rules('required');

        $form->mobile('server_phone', __('咨询电话'))->rules('required');
        $form->currency('server_price', __('咨询价格'))->symbol('￥')->rules('required');
        $form->text('server_time', __('咨询时间'))->rules('required');

        $form->image('us_image', __('关于我们图片'))->rules('required|image');
        $form->ueditor('us_description', __('关于我们内容'))->rules('required');
		
		$form->ueditor('agree', __('协议'))->rules('required');

        return $form;
    }
}
