<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller{


    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }

    public function index(){
        try {
            $products = Product::paginate(5);
            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}