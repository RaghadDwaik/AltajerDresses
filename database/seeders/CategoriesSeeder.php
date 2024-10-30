<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use App\Models\Category; // Make sure to include your Category model

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create categories
        Categories::create([
            'name' => 'New Collections',
             'description' => 'Latest arrivals in our collection.'
            ]);
        Categories::create([
            'name' => 'Sales', 
            'description' => 'Discounted items and offers.'
        ]);
        Categories::create([
            'name' => 'Corset', 
        'description' => 'Stylish and elegant corsets.'
    ]);
        Categories::create([
            'name' => 'Hijab', 
            'description' => 'Diverse range of hijabs.'
        ]);
    }
}
