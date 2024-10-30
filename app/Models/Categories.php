<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', // Optional: for category description
    ];

    // Define the relationship with products
    public function products()
    {
        return $this->hasMany(Products::class);
    }
}
