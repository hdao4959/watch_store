<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\GetItemsCartRequest;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;

class CartController extends Controller
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

    public function getItemsCart(GetItemsCartRequest $request)
    {
        try {
            $cart = collect($request->validated()['cart']);

            $cart_array = $cart->map(function ($item) {
                return [
                    'product_id' => $item['id'],
                    'size_id' => $item['size'],
                    'color_id' => $item['color'],
                ];
            });
            $cartArraySql = implode(',', $cart_array->map(function ($cart) {
                return "({$cart['product_id']}, {$cart['size_id']}, {$cart['color_id']})";
            })->toArray());


            $variants = ProductVariant::
            select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale')
            ->with([
                'product:id,name,img_thumbnail,slug',
                'color:id,name',
                'size:id,name'
            ])
                ->whereRaw("(product_id, size_id, color_id) IN ($cartArraySql)")
                ->get()
            ->map(function($variant){
                $infoProduct = optional($variant->product);
                $infoColor = optional($variant->color);
                $infoSize = optional($variant->size);
                return [
                    'product_id' => $infoProduct->id,
                    'product_name' => $infoProduct->name,
                    'img_thumbnail'=> $infoProduct->img_thumbnail,
                    // 'slug' => $infoProduct->slug,
                    'color_id' => $infoColor->id,
                    'color_name' => $infoColor->name,
                    'size_id' => $infoSize->id,
                    'size_name' => $infoSize->name,
                    'price' => $variant->price
                ];
            });
           
            return response()->json([
                'success' => true,
                'items' => $variants
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
