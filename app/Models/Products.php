<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Products extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
        'avatar',
    ];

    protected $hidden = ['category_id', 'created_at', 'updated_at'];

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Get url for avatat.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getAvatarAttribute($value)
    {
        if (!is_null($value)) {
            return asset(Storage::url($value));
        }
        return NULL;
    }
}
