<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    private function format($product) 
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'slug' => $product->slug,
            'price' => [
                'full' => $product->price,
                'discounted' => $product->discount ? $this->getCalcDiscountPrice($product) : $product->price,
            ],
            'discount' => [
                'type' => $product->discount->type,
                'amount' => $product->discount->discount,
            ],
            'images' => $product->images->pluck('path'),
        ];
    }

    private function getCalcDiscountPrice($product)
    {
        if (!$this->discount) 
        {
            return $this->price;
        }
        else 
        {
            return $this->price * ($this->discount->amount / 100);
        }
    }

    public function getById()
    {
        $product = Product::with(['images', 'discount'])->findOrFail($id);
        return response()->json($this->format($product));
    }

    public function getAll()
    {
        $product = Product::with(['images', 'discount'])->get();
        return response()->json($this->format($product));
    }

    public function getBySlug($slug)
    {
        $product = Product::with(['images', 'discount'])->where('slug', $slug)->firstOrFail();
        return response()->json($this->format($product));
    }

    public function createProduct(Request $request)
    {
        $productInfo = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'slug' => 'required|string|unique:product',
            'price' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $product->Product::create($productInfo);

        return response()->json($product, 201);
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $productInfo = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'slug' => 'string|unique:product,slug'.$id,
            'price' => 'integer',
            'active' => 'boolean',
        ]);

        $product->update($productInfo);

        return response()->json($product);
    }

    public function delete($id)
    {
        $product = Product::with(['images', 'discount'])->findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
