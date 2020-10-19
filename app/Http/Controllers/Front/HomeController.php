<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $this->data['topCategoryList'] = Category::getTopCategoryList();

        $this->data['products'] = Product::popular()->get();
        return $this->loadTheme('home', $this->data);
    }
}
