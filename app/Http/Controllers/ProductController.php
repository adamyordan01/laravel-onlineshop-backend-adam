<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:products|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'is_available' => 'required'
        ], [
            'category_id.required' => 'Kategori harus diisi',
            'name.required' => 'Nama produk harus diisi',
            'name.unique' => 'Nama produk sudah ada',
            'name.max' => 'Nama produk maksimal 255 karakter',
            'description.required' => 'Deskripsi produk harus diisi',
            'image.required' => 'Gambar produk harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa gambar dengan format jpg, png, jpeg',
            'image.max' => 'File maksimal 2048 KB',
            'price.required' => 'Harga produk harus diisi',
            'price.numeric' => 'Harga produk harus berupa angka',
            'stock.required' => 'Stok produk harus diisi',
            'stock.numeric' => 'Stok produk harus berupa angka',
            'is_available.required' => 'Status ketersediaan produk harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'errors' => $validator->errors()->toArray()
            ], 422);
        } else {
            // check if request has image
            if ($request->hasFile('image')) {
                // get image file
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/products');
                $image->move($destinationPath, $name);
            } else {
                $name = null;
            }

            $product = new Product();
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->slug = $request->name;
            $product->description = $request->description;
            $product->image = $name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->is_available = $request->is_available;
            $act = $product->save();

            if ($act) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Data berhasil disimpan'
                ], 201);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => 'Data gagal disimpan'
                ], 400);
            }
        }
    }

    public function getProductDetail(Request $request)
    {
        $productId = $request->product_id;

        $productDetails = Product::find($productId);

        $response = [];

        $response['id'] = $productDetails->id;
        $response['category_id'] = $productDetails->category_id;
        $response['name'] = $productDetails->name;
        $response['description'] = $productDetails->description;
        $response['image'] = $productDetails->image;
        $response['price'] = $productDetails->price;
        $response['stock'] = $productDetails->stock;
        $response['is_available'] = $productDetails->is_available;

        if ($productDetails) {
            $response = [
                'code' => 1,
                'message' => 'Data ditemukan',
                'data' => $productDetails
            ];
        } else {
            $response = [
                'code' => 0,
                'message' => 'Data tidak ditemukan'
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $productId = $request->product_id;

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:2048',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'is_available' => 'required'
        ], [
            'category_id.required' => 'Kategori harus diisi',
            'name.required' => 'Nama produk harus diisi',
            'name.max' => 'Nama produk maksimal 255 karakter',
            'description.required' => 'Deskripsi produk harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa gambar dengan format jpg, png, jpeg',
            'image.max' => 'File maksimal 2048 KB',
            'price.required' => 'Harga produk harus diisi',
            'price.numeric' => 'Harga produk harus berupa angka',
            'stock.required' => 'Stok produk harus diisi',
            'stock.numeric' => 'Stok produk harus berupa angka',
            'is_available.required' => 'Status ketersediaan produk harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'errors' => $validator->errors()->toArray()
            ], 422);
        } else {
            $product = Product::find($productId);

            // check if request has image
            if ($request->hasFile('image')) {
                // get image file
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/products');
                $image->move($destinationPath, $name);

                // delete old image
                if ($product->image && file_exists(storage_path('app/public/images/products/' . $product->image))) {
                    \Storage::delete('public/images/products/' . $product->image);
                }
            } else {
                $name = $product->image;
            }

            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->slug = $request->name;
            $product->description = $request->description;
            $product->image = $name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->is_available = $request->is_available;

            $act = $product->save();

            if ($act) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'code' => 2,
                    'message' => 'Data gagal diupdate'
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        // delete image
        $imagePath = public_path('/images/products/' . $product->image);

        \File::delete($imagePath);

        $act = $product->delete();

        if ($act) {
            return response()->json([
                'code' => 1,
                'message' => 'Data berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'code' => 2,
                'message' => 'Data gagal dihapus'
            ]);
        }
    }                           
}
