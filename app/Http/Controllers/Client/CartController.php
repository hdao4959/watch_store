<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\GetItemsCartRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }

    public function getItemsCart(GetItemsCartRequest $request){
        try {
            $cart = collect($request->validated()['cart']);
            $items = Product::with([
                'variants.size',
                'variants.color' 
            ])->whereIn('id', $cart->pluck('id'))
            
            ->get()->map(function($item) use ($cart){
                $product = $cart->firstWhere('id', $item->id);

                if($product){
                    $variant = $item->variants->firstWhere(function($variant) use ($product){
                        return $variant->size_id == $product['size'] && $variant->color_id == $product['color'];
                    });
                    
                    $item->variant = $variant;
                    
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'img_thumbnail' => $item->img_thumbnail,
                        'slug' => $item->slug,
                        'variant' => $item->variant,
                        'quantity' => $product['quantity']
                    ];
                }

                return null;
            })->filter()->values();


            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    } 
}
