<?php
namespace App\Repos;

use App\Models\Product;
use App\Repos\RepoBase;

class ProductRepo extends RepoBase
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function create($data)
    {
        return Product::insertGetId($data);
    }
}