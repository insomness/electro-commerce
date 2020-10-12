<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    protected $appends = ['full_name'];

    public const PENDING = 'pending';
    public const SHIPPED = 'shipped';


    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
