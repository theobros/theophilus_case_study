<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Get the product associated with the cart items.
     */
    public function products()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }
}
