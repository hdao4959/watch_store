<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller{

    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }
    public function show(string $slug){
        try {
            $product = Product::with([
                'category.parent',
                'variants.size',
                'variants.color',
            ])->where('slug', $slug)->first();
            if(!$product){
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm này không tồn tại!'
                ],404 );
            }
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    public function productsOfCategory(string $slug){
        try {
            $category = Category::with('children')->where('slug', $slug)->first();
            if(!$category){
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục này không tồn tại!'
                ],404);
            }
            $products = Product::whereIn('category_id', $category->children->pluck('id'))->orWhere('category_id', $category->id)->get();
           
            if($products->isEmpty()){
                return response()->json([
                    'success' => true,
                    'message' => 'Không có sản phẩm nào!'
                ],200);
            }


            return response()->json([
                'success' => true,
                'products' => $products
            ]);


        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
    
}