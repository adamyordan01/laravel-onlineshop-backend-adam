<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'description.required' => 'Deskripsi kategori harus diisi',
            'image.required' => 'Gambar kategori harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa gambar dengan format jpg, png, jpeg',
            'image.max' => 'File maksimal 2048 KB'
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
                $destinationPath = public_path('/images/categories');
                $image->move($destinationPath, $name);
            } else {
                $name = null;
            }

            $category = new Category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->description = $request->description;
            $category->image = $name;
            $act = $category->save();

            if ($act) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Kategori berhasil disimpan'
                ]);
            } else {
                return response()->json([
                    'code' => 2,
                    'message' => 'Kategori gagal disimpan'
                ]);
            }
        }
    }

    public function getCategoryDetail(Request $request)
    {
        $categoryId = $request->category_id;

        $categoryDetails = Category::find($categoryId);

        $response = [];

        $response['id'] = $categoryDetails->id;
        $response['name'] = $categoryDetails->name;
        $response['description'] = $categoryDetails->description;
        $response['image'] = $categoryDetails->image;

        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $categoryId = $request->category_id;

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories')->ignore($categoryId)
            ],
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'description.required' => 'Deskripsi kategori harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa gambar dengan format jpg, png, jpeg',
            'image.max' => 'File maksimal 2048 KB'
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
                $destinationPath = public_path('/images/categories');
                $image->move($destinationPath, $name);
            } else {
                $name = $request->current_image;
            }

            $category = Category::find($categoryId);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->description = $request->description;
            $category->image = $name;
            $act = $category->save();

            if ($act) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Kategori berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'code' => 2,
                    'message' => 'Kategori gagal diupdate'
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        // delete image
        $imagePath = public_path('/images/categories/' . $category->image);
        
        \File::delete($imagePath);

        $act = $category->delete();

        if ($act) {
            return response()->json([
                'code' => 1,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'code' => 2,
                'message' => 'Kategori gagal dihapus'
            ]);
        }
    }
}
