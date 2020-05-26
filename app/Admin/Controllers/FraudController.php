<?php

namespace App\Admin\Controllers;

use App\Models\Fraud;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FraudController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '骗局盘点';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Fraud());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('content', __('亏损方式'))->hide();
        $grid->column('description', __('入金方式'))->hide();
        $grid->column('sort_order', __('Sort order'))->sortable()->editable()->help('按数字大小正序排序');
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
        $show = new Show(Fraud::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('content', __('Content'));
        $show->field('description', __('Description'));
        $show->field('sort_order', __('Sort order'));
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
        $form = new Form(new Fraud());

        $form->text('name', __('Name'))->rules('required');
        $form->textarea('content', __('亏损方式'))->rules('required');
        $form->textarea('description', __('入金方式'))->rules('required');
        $form->number('sort_order', __('Sort order'))->default(99);

        return $form;
    }
}
