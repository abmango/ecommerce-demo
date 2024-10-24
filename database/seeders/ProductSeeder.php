<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        for ($i=0; $i < 10; $i++) { 


            $min = 20 * 100;    
            $max = 50000 * 100; 
            $randomCents = mt_rand($min, $max);
            $randomPrice = number_format($randomCents / 100, 2, '', '.');   

            Product::create([
                'name' => Str::random(6),
                'description' => Str::random(30),
                'price' => $randomPrice,
                'image' => 'https://picsum.photos/seed/' . uniqid() . '/200/300',
                'stock' => '10',
                'type' => 'car'
            ]);            
        }
    }
}
