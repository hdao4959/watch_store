<?php 
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller{

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
            $categories = Category::with('children')->whereNull('parent_id')->get();
            return response()->json([
                'success' => true, 
                'categories' => $categories
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    
}