<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->q, function ($products) use ($request) {
            $products = $products->where('name', 'like', '%' . $request->q . '%');
        })->when($request->category_id, function ($products) use ($request) {
            $products = $products->where('category_id', $request->category_id);
        })->when($request->sort, function ($products) use ($request) {
            $products = $products->orderBy('price', $request->sort);
        })->paginate(10);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $products
        ], 200);
    }

    public function getProductByCategory($id)
    {
        $products = Product::whereHas('category', function ($query) use ($id) {
            $query->where('id', $id);
        })->paginate(10);

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $products
        ], 200);
    }
}
