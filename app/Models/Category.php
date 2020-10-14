<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getTopCategoryList()
    {
        $categories = self::withCount('products')->latest('products_count')->take(5)->get();
        $categories->transform(function ($category) {
            $category->products = Product::whereHas('category', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
                ->active()
                ->take(5)
                ->latest()
                ->with(['category', 'productImages'])
                ->get();
            return $category;
        });

        return $categories;
    }
}
