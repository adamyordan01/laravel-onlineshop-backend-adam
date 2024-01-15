<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC')->paginate(10);
        
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 
                Rule::unique('categories')->ignore($request->id)
            ],
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada',
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
                    'code' => 200,
                    'message' => 'success',
                    'data' => $category
                ], 200);
            } else {
                return response()->json([
                    'code' => 500,
                    'message' => 'internal server error',
                    'data' => null
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
