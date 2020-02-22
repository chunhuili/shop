<?php
/**
 * Created by PhpStorm.
 * User: lichunhui
 * Date: 2020/2/19
 * Time: 下午9:03
 */

namespace App\Admin\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Profit;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ProfitController extends Controller
{
    use HasResourceActions;

    /**
     * 收益列表
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('收益列表')
            ->body($this->grid());
    }


    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑商品类目')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
//    public function create(Content $content)
//    {
//        return $content
//            ->header('创建商品类目')
//            ->body($this->form());
//    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Profit());

        $grid->id('ID')->sortable();
        $grid->name('姓名');
        $grid->type('身份')->display(function ($value) {
            if ($value == 1) {
                return "主播";
            } elseif ($value == 2) {
                return "经纪人";
            } else {
                return "运营商";
            }
        });
        $grid->sales_revenue('销售收益');
        $grid->invite_revenue('邀请收益');
        $grid->team_revenue('团队管理收益');
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableFilter();
        $grid->actions(function ($actions) {
            // 不展示 Laravel-Admin 默认的查看按钮
//            $actions->disableCreate();
            $actions->disableView();
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
        $show = new Show(Category::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->parent_id('Parent id');
        $show->is_directory('Is directory');
        $show->level('Level');
        $show->path('Path');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @param bool $isEditing
     * @return Form
     */
    protected function form($isEditing = false)
    {
        // Laravel-Admin 1.5.19 之后的新写法，原写法也仍然可用
        $form = new Form(new Category);

        $form->text('name', '类目名称')->rules('required');

        // 如果是编辑的情况
        if ($isEditing) {
            // 不允许用户修改『是否目录』和『父类目』字段的值
            // 用 display() 方法来展示值，with() 方法接受一个匿名函数，会把字段值传给匿名函数并把返回值展示出来
            $form->display('is_directory', '是否目录')->with(function ($value) {
                return $value ? '是' :'否';
            });
            // 支持用符号 . 来展示关联关系的字段
            $form->display('parent.name', '父类目');
        } else {
            // 定义一个名为『是否目录』的单选框
            $form->radio('is_directory', '是否目录')
                ->options(['1' => '是', '0' => '否'])
                ->default('0')
                ->rules('required');

            // 定义一个名为父类目的下拉框
            $form->select('parent_id', '父类目')->ajax('/admin/api/categories');
        }

        return $form;
    }

    // 定义下拉框搜索接口
    public function apiIndex(Request $request)
    {
        // 用户输入的值通过 q 参数获取
        $search = $request->input('q');
        $result = Category::query()
            // 通过 is_directory 参数来控制
            ->where('is_directory', boolval($request->input('is_directory', true)))
//            ->where('is_directory', true)  // 由于这里选择的是父类目，因此需要限定 is_directory 为 true
            ->where('name', 'like', '%'.$search.'%')
            ->paginate();

        // 把查询出来的结果重新组装成 Laravel-Admin 需要的格式
        $result->setCollection($result->getCollection()->map(function (Category $category) {
            return ['id' => $category->id, 'text' => $category->full_name];
        }));

        return $result;
    }
}