<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = ['customer_full_name'];

    public const CREATED = 'created';
    public const CONFIRMED = 'confirmed';
    public const DELIVERED = 'delivered';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public const ORDERCODE = 'INV';

    public const PAID = 'paid';
    public const UNPAID = 'unpaid';

    public const STATUSES = [
        self::CREATED => 'Created',
        self::CONFIRMED => 'Confirmed',
        self::DELIVERED => 'Delivered',
        self::COMPLETED => 'Completed',
        self::CANCELLED => 'Cancelled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        $columns = [
            'qty',
            'base_price',
            'base_total',
            'tax_amount',
            'tax_percent',
            'sub_total',
            'sku',
            'name',
            'weight',
        ];

        return $this->belongsToMany(Product::class, 'order_items')->withPivot($columns)->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public static function generateOrderCode()
    {
        $dateCode = self::ORDERCODE . '/' . date('Ymd') . '/' . numberToRomanRepresentation(date('m')) . '/' . numberToRomanRepresentation(date('d')) . '/';

        $lastOrder = self::select([\DB::raw('MAX(orders.code) AS last_code')])
            ->where('code', 'like', $dateCode . '%')
            ->first();

        $lastOrderCode = !empty($lastOrder) ? $lastOrder['last_code'] : null;

        $orderCode = $dateCode . '00001';
        if ($lastOrderCode) {
            $lastOrderNumber = str_replace($dateCode, '', $lastOrderCode);
            $nextOrderNumber = sprintf('%05d', (int)$lastOrderNumber + 1);

            $orderCode = $dateCode . $nextOrderNumber;
        }

        if (self::_isOrderCodeExists($orderCode)) {
            return generateOrderCode();
        }

        return $orderCode;
    }

    public function scopeForUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }

    private static function _isOrderCodeExists($orderCode)
    {
        return Order::where('code', '=', $orderCode)->exists();
    }

    public function isPaid()
    {
        return $this->payment_status == self::PAID;
    }

    public function isCreated()
    {
        return $this->status == self::CREATED;
    }

    public function isConfirmed()
    {
        return $this->status == self::CONFIRMED;
    }

    public function isDelivered()
    {
        return $this->status == self::DELIVERED;
    }

    public function isCompleted()
    {
        return $this->status == self::COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status == self::CANCELLED;
    }

    public function getCustomerFullNameAttribute()
    {
        return "{$this->customer_first_name} {$this->customer_last_name}";
    }
}
