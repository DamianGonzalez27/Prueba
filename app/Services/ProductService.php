<?php
namespace App\Services;

use App\Repos\ProductRepo;
use App\Exceptions\ItemNotFound;
use App\Exceptions\UserNotStoreRegistered;
use Illuminate\Support\Str;

class ProductService
{
    private $productRepo;

    public function __construct(ProductRepo $productRepo)
    {   
        $this->productRepo = $productRepo;
    }

    public function create($name, $description, $price, $image, $quantity)
    {
        $this->productRepo->create([
            'name' => $name,
            'description' => $description,
            'sku' => $this->generateSKU($name),
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'isActive' => true
        ]);
        return 'Product created!';
    }

    public function getById($id)
    {
        $product = $this->productRepo->findById($id);
        if(!$product)
            throw new ItemNotFound("Product not found");
        return $product;
    }

    public function getAll($name = null, $sku = null, $range = null, $isActive = true)
    {
        $filter = [];

        if(!is_null($name))
            $filter['name'] = $name;

        if(!is_null($sku))
            $filter['sku'] = $sku;

        if(!is_null($range))
            $filter['range'] = $range;

        $filter['isActive'] = $isActive;

        $products = $this->productRepo->find(['*'], $filter);

        if(!$products)
            throw new ItemNotFound("Products not found");
        return $products;
    }

    public function delete($id)
    {
        return $this->productRepo->updateColumn($id, ['isActive' => false]);
    }

    public function generateSKU($productName)
    {
        $lastThreeLetters = Str::substr($productName, -3);
        $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $sku = 'pro-' . $randomNumber . $lastThreeLetters;
        return $sku;
    }
}