<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'orders_id',
        'products_id',
        'product_name',
        'size',
        'color',
        'quantity',
        'price',
        'total',
    ];

    /**
     * Get the order that owns the order detail.
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'orders_id'); 
    }

    /**
     * Get the product associated with the order detail.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'products_id'); 
    }
}
