<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cart_data',
    ];

    public function setCartDataAttribute($value)
    {
        $this->attributes['cart_data'] = serialize($value);
    }

    public function getCartDataAttribute($value)
    {
        return unserialize($value);
    }
}

class DBStorage
{

    public function has($key)
    {
        return Wishlist::find($key);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return new \CartCollection(Wishlist::find($key)->cart_data);
        } else {
            return [];
        }
    }

    public function put($key, $value)
    {
        if ($row = Wishlist::find($key)) {
            // update
            $row->cart_data = $value;
            $row->save();
        } else {
            Wishlist::create([
                'id' => $key,
                'cart_data' => $value
            ]);
        }
    }
}
