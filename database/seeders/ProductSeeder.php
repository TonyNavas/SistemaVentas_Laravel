<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::deleteDirectory('public/products');
        Storage::makeDirectory('public/products');

        Product::factory(50)->create()->each(function(Product $product){
            $faker = Faker::create();
            $product->image()->create(['url'=>'products/'.$faker->image('public/storage/products',640,480,'Product',false)]);
        });
    }
}
