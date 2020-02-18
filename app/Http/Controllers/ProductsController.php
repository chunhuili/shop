<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\SearchBuilders\ProductSearchBuilder;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\Exception;
use Symfony\Component\Process\Process;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page',1);
        $perPage = 16;
        // 新建查询构造器对象，设置只搜索上架商品，设置分页
        $builder = (new ProductSearchBuilder())->onSale()->paginate($perPage,$page);

        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))){
            $builder->category($category);
        }

        //只有当用户有输入搜索词或者使用了类目筛选的时候才会做聚合
        if ($search = $request->input('search', '')) {
            $keywords = array_filter(explode(' ',$search));
            // 调用查询构造器的关键词筛选
            $builder->keywords($keywords);
        }

        if ($search || isset($category)) {
            // 调用查询构造器的分面搜索
            $builder->aggregateProperties();
        }

        $propertyFilters = [];
        if ($filterString = $request->input('filters')) {
            $filterArray = explode('|', $filterString);
            foreach ($filterArray as $filter) {
                list($name, $value) = explode(':', $filter);
                $propertyFilters[$name] = $value;
                // 调用查询构造器的属性筛选
                $builder->propertyFilter($name, $value);
            }
        }


        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order','')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/',$order,$m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1],['price','sold_count','rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

//        $result = app('es')->search($builder->getParams());

        //通过collect函数将返回结果转为集合，并通过集合的pluck方法取到返回商品ID数组
//        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
        // 通过 whereIn 方法从数据库中读取商品数据
//        $products = Product::query()
//            ->byIds($productIds)
//            ->whereIn('id',$productIds)
//            // orderByRaw 可以让我们用原生的 SQL 来给查询结果排序
//            ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))
//            ->get();
        // 返回一个 LengthAwarePaginator 对象
//        $pager = new LengthAwarePaginator($products, $result['hits']['total'], $perPage, $page,
//            [
//                'path' => route('products.index',false),
//            ]);


        $properties = [];
        // 如果返回结果里有 aggregations 字段，说明做了分面搜索
        if (isset($result['aggregations'])) {
            // 使用 collect 函数将返回值转为集合
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])
                ->map(function ($bucket) {
                    // 通过 map 方法取出我们需要的字段
                    return [
                        'key'    => $bucket['key'],
                        'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                    ];
                })->filter(function($property) use ($propertyFilters) {
                    //过滤掉只剩下一个值或者已经在筛选条件里的属性
                    return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]);
                });
        }

        return view('products.index',[
//            'products' => $pager,
            'products' => '',
            'filters' => [
                'search' => '',
                'order' => $order
            ],
            // 等价于 isset($category) ? $category : null
            'category' => $category ?? null,
            'properties' => $properties,
            'propertyFilters' => $propertyFilters,
            // 将类目树传递给模板文件
//            'categoryTree' => $categoryService->getCategoryTree(),
        ]);
    }

    public function show(Product $product,Request $request,ProductService $service)
    {
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }
        $favored = false;
        // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
        if ($user = $request->user()) {
            // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OrderItem::query()
            ->with(['order.user', 'productSku']) // 预先加载关联关系
            ->where('product_id', $product->id)
            ->whereNotNull('reviewed_at') // 筛选出已评价的
            ->orderBy('reviewed_at', 'desc') // 按评价时间倒序
            ->limit(10) // 取出 10 条
            ->get();
        $similarProductIds = $service->getSimilarProductIds($product,4);
        // 根据 Elasticsearch 搜索出来的商品 ID 从数据库中读取商品数据
        $similarProducts = Product::query()->byIds($similarProductIds)->get();
//            ->whereIn('id', $similarProductIds)
//            ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $similarProductIds)))
//            ->get();

        return view('products.show',['product' => $product,'favored' => $favored,'reviews' => $reviews,'similar'=>$similarProducts ]);
    }

    public function favor(Product $product, Request $request)
    {
        $user = $request->user();
        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);

        return [];
    }

    public function disfavor(Product $product,Request $request)
    {
        $user = $request->user();
        $user->favoriteProducts()->detach($product);
    }

    public function favorites(Request $request)
    {
        $product = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites',['products' => $product]);
    }
}
