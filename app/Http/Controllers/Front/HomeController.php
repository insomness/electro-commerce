<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $this->data['topCategoryList'] = Category::getTopCategoryList();

        return $this->loadTheme('home', $this->data);
    }
}
