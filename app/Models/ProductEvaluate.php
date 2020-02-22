<?php
/**
 * Created by PhpStorm.
 * User: lichunhui
 * Date: 2020/2/22
 * Time: 下午9:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductEvaluate extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}