<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $table = 'product_datas';
    
    protected $fillable = ['size', 'color','image', 'stock_quantity'];


    public function product()
    {
        return $this->belongsTo(Products::class);
    }

}
