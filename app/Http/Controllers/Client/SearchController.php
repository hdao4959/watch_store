<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function handleErrorNotDefine($th)
    {
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],
            500
        );
    }


    public function search(Request $request)
    {
        try {

            $query = $request->all()['query'];

            $products = Product::where('name', "LIKE", "%$query%")->get();
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Không tìm thấy sản phẩm nào!'
                ], 404);
            }

            return response()->json([
                'products' => $products
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
