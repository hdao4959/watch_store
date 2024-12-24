<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
            $product = Product::where('slug', $slug)->first();
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
}