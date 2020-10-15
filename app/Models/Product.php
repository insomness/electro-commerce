<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use Rateable;

    protected $guarded = [
        'category'
    ];

    public const DRAFT = 0;
    public const ACTIVE = 1;
    public const INACTIVE = 2;

    public const STATUSES = [
        self::DRAFT => 'draft',
        self::ACTIVE => 'active',
        self::INACTIVE => 'inactive',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    /*
     * Scope popular products
	 *
	 * @param Eloquent $query query builder
	 * @param int      $limit limit
	 *
	 * @return Eloquent
	 */
    public function scopePopular($query, $limit = 10)
    {
        $month = now()->format('m');

        return $query->selectRaw('products.*, COUNT(order_items.id) as total_sold')
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereRaw(
                'orders.status = :order_satus AND MONTH(orders.order_date) = :month',
                [
                    'order_status' => Order::COMPLETED,
                    'month' => $month
                ]
            )
            ->groupBy('products.id')
            ->orderByRaw('total_sold DESC')
            ->limit($limit);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
