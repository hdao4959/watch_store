<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
        ->with(['children' ])->latest('id')
        ->get();
        return response()->json($categories);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategory $request)
    {
        try {
            $data = $request->validated();
        $slug = Str::slug($data['name']);
        Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?? null
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Thêm danh mục thành công!'
        ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra ' . $th
            ]);
        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $category = Category::where('id', $id)->first();

            if(!$category){
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục này không tồn tại'
                ],404);
            }
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xoá danh mục thành công!'
            ],200);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
