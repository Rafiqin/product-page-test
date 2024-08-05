<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductDiscount;

class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create([
            'name' => "Fall Limited Edition Sneakers",
            'description' => "These low-profile sneakers are your perfect casual wear companion. Featuring a durable rubber outer sole, they'll withstand everything the weather can offer.",
            'slug' => "fall-limited-edition-sneakers",
            'price' => 200.00,
            'active' => true,
        ]);

        $imagePaths = [
            '/images/image-product-1.jpg',
            '/images/image-product-2.jpg',
            '/images/image-product-3.jpg',
            '/images/image-product-4.jpg', 
        ];

        foreach ($imagePaths as $imagePath) {
            ProductImages::create([
                'product_id' => $product->id,
                'path' => $imagePath
            ]);
        }

        productDiscount::create([
            'product_id' => $product->id,
            'type' => 'percent',
            'discount' => 50,
        ]);
    }
}
