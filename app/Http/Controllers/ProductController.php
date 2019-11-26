<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Product;
use Illuminate\Http\Request;
use JWTAuth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function all()
    {
        $products = Product::findByUser($this->user->id)->get();

        return response()->json([
            'products' => $products
        ]);
    }

    public function detail($productId)
    {
        $product = Product::find($productId);

        return response()->json([
            'product' => $product
        ]);
    }

    public function create(ProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->user_id = $this->user->id;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'New product has been stored.',
            'product' => $product
        ]);
    }

    public function update(ProductRequest $request, $productId)
    {
        $product = Product::find($productId);
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->user_id = $this->user->id;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product has been updated.',
            'product' => $product
        ]);
    }

    public function delete($productId)
    {
        $product = Product::find($productId);
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product has been deleted.'
        ]);
    }
}
