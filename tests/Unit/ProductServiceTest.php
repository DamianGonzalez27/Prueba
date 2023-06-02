<?php

namespace Tests\Unit;

use Mockery;
use App\Repos\ProductRepo;
use PHPUnit\Framework\TestCase;
use App\Exceptions\ItemNotFound;
use App\Services\ProductService;
use App\Exceptions\UserNotStoreRegistered;

class ProductServiceTest extends TestCase
{

    protected $productRepo;
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepo = Mockery::mock(ProductRepo::class);
        $this->service = new ProductService($this->productRepo);
    }

    public function testCreateSuccess(): void
    {
        $this->productRepo->shouldReceive('create')->once()->andReturn(true);
        $this->assertEquals(
            'Product created!',
            $this->service->create('name', 'description', 102, 'image', 10)
        );
    }

    public function testGetByIdNotFound(): void
    {
        $this->productRepo->shouldReceive('findById')->once()->andReturn(null);
        $this->expectException(ItemNotFound::class);
        $this->service->getById(1);
    }

    public function testgetByIdFounded(): void
    {
        $this->productRepo->shouldReceive('findById')->once()->andReturn(["id" => 1]);
        $this->assertEquals(['id' => 1], $this->service->getById(1));
    }

    public function testGetAllNotFound(): void
    {
        $this->productRepo->shouldReceive('find')->once()->andReturn(null);
        $this->expectException(ItemNotFound::class);
        $this->service->getAll(1);
    }

    public function testGetAllSuccess(): void
    {
        $this->productRepo->shouldReceive('find')->once()->andReturn([['id' => 1]]);
        $this->assertEquals([['id' => 1]], $this->service->getAll());
    }

    
}