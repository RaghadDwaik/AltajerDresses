<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock_quantity',
        'images',    

        'category_id',
    ];

   
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function productData()
{
    return $this->hasMany(ProductData::class);
}



public function lovedByUsers()
{
    return $this->belongsToMany(User::class, 'loves', 'products_id', 'user_id');
}

}
