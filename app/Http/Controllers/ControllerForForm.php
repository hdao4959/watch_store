<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ControllerForForm extends Controller
{

    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }
    public function parentCategories(){
        try {
            $parentCategories = Category::whereNull('parent_id')->select('id', 'name')->get();
            return response()->json([
                'success' => true,
                 'parentCategories' => $parentCategories
            ]);
        } catch (\Throwable $th){
            return $this->handleErrorNotDefine($th);
        }
    }
    public function AllCategories(){
        try {
            $parentCategories = Category::whereNull('parent_id')->with([
                'children' => function($query){
                    $query->select('id', 'name', 'parent_id');
                }
            ])->select('id', 'name')->get();
            return response()->json([
                'success' => true,
                 'allCategories' => $parentCategories
            ]);
        } catch (\Throwable $th){
            return $this->handleErrorNotDefine($th);
        }
    }

    
}
