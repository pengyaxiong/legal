<?php

namespace App\Admin\Controllers;

use App\Models\Lighthouse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LighthouseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '曝光台';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lighthouse());

        $grid->column('id', __('Id'));
        $grid->column('name', __('平台名称'));
        $grid->column('url', __('平台网址'))->hide();
        $grid->column('phone', __('联系方式'))->hide();
        $grid->column('image', __('Image'))->image()->hide();
        $grid->column('logo', __('Logo'))->image()->hide();
        $grid->column('type', __('Type'))->pluck('name')->label();;
        $grid->column('sort_order', __('Sort order'))->sortable()->editable()->help('按数字大小正序排序');
        $states = [
            'on' => ['value' => 1, 'text' => '曝光', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '查询中', 'color' => 'danger'],
        ];
        $grid->column('is_show', __('Is show'))->switch($states);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Lighthouse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('url', __('Url'));
        $show->field('phone', __('Phone'));
        $show->field('image', __('Image'));
        $show->field('logo', __('Logo'));
        $show->field('type', __('Type'));
        $show->field('sort_order', __('Sort order'));
        $show->field('is_show', __('Is show'));
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
        $form = new Form(new Lighthouse());

        $form->text('name', __('平台名称'))->rules('required');
        $form->url('url', __('平台网址'))->rules('required');
        $form->mobile('phone', __('联系方式'));
        $form->image('image', __('Image'))->rules('required|image');
        $form->image('logo', __('地区小图标'))->rules('required|image');
        $form->table('type', __('Type'), function ($table) {
            $table->text('name', __('Type'));
        })->rules('required');
        $form->number('sort_order', __('Sort order'))->default(99);

        $states = [
            'on' => ['value' => 1, 'text' => '曝光', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '查询中', 'color' => 'danger'],
        ];
        $form->switch('is_show', __('Is show'))->states($states);

        return $form;
    }
}
