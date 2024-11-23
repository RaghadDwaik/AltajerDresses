<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Ensure the Product model is included
use App\Models\ProductData;
use App\Models\Products;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'product_name' => 'Elegant Corset',
                'description' => 'An elegant corset that enhances your silhouette.',
                'price' => 49.99,
                'stock_quantity' => 100,
                'images' => json_encode(['images/home/dress2.jpeg', 'images/home/dress4.jpeg']),
                'category_id' => 1,
                'variants' => json_encode([
                    ['size' => 38, 'color' => 'Black', 'stock_quantity' => 20, 'image' => 'images/home/dress2.jpeg'],
                    ['size' => 40, 'color' => 'White', 'stock_quantity' => 15, 'image' => 'images/home/dress4.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Stylish Hijab',
                'description' => 'A stylish hijab made from soft fabric.',
                'price' => 19.99,
                'stock_quantity' => 200,
                'images' => json_encode(['images/home/dress2.jpeg', 'images/home/dress2.jpeg']),
                'category_id' => 2,
                'variants' => json_encode([
                    ['size' => 36, 'color' => 'Cream', 'stock_quantity' => 50,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 38, 'color' => 'Black', 'stock_quantity' => 50,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Summer Dress',
                'description' => 'A beautiful summer dress perfect for warm weather.',
                'price' => 29.99,
                'stock_quantity' => 150,
                'images' => json_encode(['images/home/dress3.jpeg']),
                'category_id' => 3,
                'variants' => json_encode([
                    ['size' => 40, 'color' => 'Yellow', 'stock_quantity' => 20,'image' => 'images/home/dress1.jpeg'],
                    ['size' => 42, 'color' => 'Pink', 'stock_quantity' => 15,'image' => 'images/home/dress3.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Casual T-Shirt',
                'description' => 'A comfortable and casual t-shirt.',
                'price' => 15.99,
                'stock_quantity' => 300,
                'images' => json_encode(['images/home/dress1.jpeg']),
                'category_id' => 4,
                'variants' => json_encode([
                    ['size' => 'M', 'color' => 'Blue', 'stock_quantity' => 40,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'L', 'color' => 'Gray', 'stock_quantity' => 30,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Formal Blazer',
                'description' => 'A sleek and stylish formal blazer.',
                'price' => 89.99,
                'stock_quantity' => 50,
                'images' => json_encode(['images/home/dress4.jpeg']),
                'category_id' => 1,
                'variants' => json_encode([
                    ['size' => 'S', 'color' => 'Black', 'stock_quantity' => 10,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'M', 'color' => 'Navy', 'stock_quantity' => 15,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Denim Jacket',
                'description' => 'A classic denim jacket.',
                'price' => 59.99,
                'stock_quantity' => 80,
                'images' => json_encode(['images/home/dress1.jpeg']),
                'category_id' => 2,
                'variants' => json_encode([
                    ['size' => 'M', 'color' => 'Blue', 'stock_quantity' => 20,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'L', 'color' => 'Light Blue', 'stock_quantity' => 25,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Classic Sneakers',
                'description' => 'Comfortable and stylish sneakers for everyday wear.',
                'price' => 49.99,
                'stock_quantity' => 120,
                'images' => json_encode(['images/home/dress3.jpeg']),
                'category_id' => 3,
                'variants' => json_encode([
                    ['size' => 42, 'color' => 'White', 'stock_quantity' => 30,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 44, 'color' => 'Black', 'stock_quantity' => 25,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Summer Hat',
                'description' => 'A wide-brimmed hat perfect for sunny days.',
                'price' => 24.99,
                'stock_quantity' => 90,
                'images' => json_encode(['images/home/dress2.jpeg']),
                'category_id' => 4,
                'variants' => json_encode([
                    ['size' => 'One Size', 'color' => 'Beige', 'stock_quantity' => 40,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'One Size', 'color' => 'Brown', 'stock_quantity' => 50,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Summer Hat',
                'description' => 'A wide-brimmed hat perfect for sunny days.',
                'price' => 24.99,
                'stock_quantity' => 90,
                'images' => json_encode(['images/home/dress1.jpeg']),
                'category_id' => 1,
                'variants' => json_encode([
                    ['size' => 'One Size', 'color' => 'Beige', 'stock_quantity' => 40,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'One Size', 'color' => 'Brown', 'stock_quantity' => 50,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
            [
                'product_name' => 'Summer Hat',
                'description' => 'A wide-brimmed hat perfect for sunny days.',
                'price' => 24.99,
                'stock_quantity' => 90,
                'images' => json_encode(['images/home/dress4.jpeg']),
                'category_id' => 1,
                'variants' => json_encode([
                    ['size' => 'One Size', 'color' => 'Beige', 'stock_quantity' => 40,'image' => 'images/home/dress2.jpeg'],
                    ['size' => 'One Size', 'color' => 'Brown', 'stock_quantity' => 50,'image' => 'images/home/dress2.jpeg'],
                ]),
            ],
        ];

        foreach ($products as $productData) {
            // Decode the variants JSON string into an array
            $productVariants = json_decode($productData['variants'], true);
            unset($productData['variants']);

            // Insert the product data
            $product = Products::create($productData);

            // Check if $productVariants is an array before iterating
            if (is_array($productVariants)) {
                // Insert each variant for this product
                foreach ($productVariants as $variant) {
                    $variant['products_id'] = $product->id;
                    ProductData::create($variant);
                }
            }
        }
    }
}