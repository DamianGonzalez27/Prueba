<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProductService;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    protected $service;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Crear 15 productos
        for ($i = 1; $i <= 15; $i++) {
            $name = 'Producto ' . $i;
            Product::create([
                'name' => $name,
                'description' => rand(10, 100),
                'sku' => $this->service->generateSKU($name),
                'price' => rand(1, 9) * $i,
                'quantity' => rand(1, 9),
                'image' => 'https://www.hersheyland.mx/content/dam/Hersheyland_Mexico/es_mx/products/barras/hersheys-barras-almendra-6-38g/Hersheys-barras-almendra-6-38g-front.png',
                'isActive' => (bool) rand(0, 1)
            ]);
        }
    }
}
