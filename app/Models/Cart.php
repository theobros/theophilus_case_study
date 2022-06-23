<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'user_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];


    /**
     * Get the cart items for the cart.
     */
    public function cart_items()
    {
        return $this->hasMany(CartItems::class);
    }
}
