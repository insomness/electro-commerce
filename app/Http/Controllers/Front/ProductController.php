<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->data['minPrice'] = Product::min('price');
        $this->data['maxPrice'] = Product::max('price');

        $this->data['sorts'] = [
            url('products') => 'Default',
            url('products?sort=price-asc') => 'Price - Low to High',
            url('products?sort=price-desc') => 'Price - High to Low',
            url('products?sort=created_at-desc') => 'Newest to Oldest',
            url('products?sort=created_at-asc') => 'Oldest to Newest',
        ];

        $this->data['selectedSort'] = url('products');
    }

    public function index(Request $request)
    {

        $products = Product::active();

        $products = $this->_filterProductsByPriceRange($products, $request);
        $products = $this->_sortProducts($products, $request);

        $items = $request->items ?? 10;
        $this->data['items'] = $items;
        $this->data['products'] = $products->paginate($items);

        return $this->loadTheme('products.index', $this->data);
    }

    /**
     * Filter products by price range
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function _filterProductsByPriceRange($products, $request)
    {
        $lowPrice = null;
        $highPrice = null;

        if ($request->query('price-min') && $request->query('price-max')) {

            $lowPrice = preg_replace('/\D/', '', $request->query('price-min'));
            $highPrice = preg_replace('/\D/', '', $request->query('price-max'));

            if ($lowPrice && $highPrice) {
                $products = $products->where('price', '>=', $lowPrice)
                    ->where('price', '<=', $highPrice);

                $this->data['minPrice'] = $lowPrice;
                $this->data['maxPrice'] = $highPrice;
            }
        }

        return $products;
    }

    /**
     * Sort products
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function _sortProducts($products, $request)
    {
        if ($sort = preg_replace('/\s+/', '', $request->query('sort'))) {
            $availableSorts = ['price', 'created_at'];
            $availableOrder = ['asc', 'desc'];
            $sortAndOrder = explode('-', $sort);

            $sortBy = strtolower($sortAndOrder[0]);
            $orderBy = strtolower($sortAndOrder[1]);

            if (in_array($sortBy, $availableSorts) && in_array($orderBy, $availableOrder)) {
                $products = $products->orderBy($sortBy, $orderBy);
            }

            $this->data['selectedSort'] = url('products?sort=' . $sort);
        }

        return $products;
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug product slug
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::active()->where('slug', $slug)->first();

        if (!$product) {
            return redirect('products');
        }

        $this->data['product'] = $product;

        return $this->loadTheme('products.show', $this->data);
    }
}
