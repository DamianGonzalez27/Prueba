<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;

class ProductsController extends Controller
{
    private $producService;

    public function __construct(ProductService $producService)
    {
        $this->producService = $producService;
    }

    public function index(Request $request)
    {
        return $this->responseWhidthData(
            'products', 
            $this->producService->getAll(
                $request->query->get('name'),
                $request->query->get('sku'),
                json_decode($request->query->get('range'))
            )
        );
    }

    public function show($id)
    {
        return $this->responseWhidthData(
            'product', 
            $this->producService->getById($id)
        );
    }

    public function create(CreateProductRequest $request)
    {
        return $this->basicResponse(
            $this->producService->create(
                $request->input('name'),
                $request->input('description'),
                $request->input('price'),
                $request->input('image'),
                $request->input('quantity')
            )
        );
    }

    public function delete($id)
    {
        return $this->basicResponse(
            $this->producService->delete($id)
        );
    }

}
