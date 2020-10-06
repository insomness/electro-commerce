<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded = [];

    public const UPLOAD_FOLDER = 'products/images/';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
