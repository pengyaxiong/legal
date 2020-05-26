<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '栏目管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'))->modal(function ($model) {

            $articles = $model->articles()->take(10)->get()->map(function ($article) {
                return $article->only(['id', 'title', 'description']);
            });

            $array=$articles->toArray();
            foreach ($array as $k=>$v){
                $url=route('articles.edit',$v['id']);
                $array[$k]['edit']='<div class="btn">
              <a class="btn btn-sm btn-default pull-right" target="_blank" href="'.$url.'" rel="external" >
              <i class="fa fa-edit"></i> 编辑</a>
                 </div>';
            }
            return new Table(['ID', '内容', '简介','操作'], $array);
        });

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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
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
        $form = new Form(new Category());

        $form->text('name', __('Name'));
        $form->number('sort_order', __('Sort order'))->default(99);

        return $form;
    }
}
