<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\GetItemsCartRequest;
use App\Http\Requests\Cart\OrderRequest;
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

    public function order(OrderRequest $request){
        try {
            $data = $request->validated();
            $cart = collect($data['cart']);
            $cartMap = $cart->mapWithKeys(function($item){
                return [implode('_', [$item['id'], $item['size'], $item['color']])  => $item['quantity']];
            });

            $cart_array = implode(',', $cart->map(function($item){
                return "({$item['id']}, {$item['size']}, {$item['color']})";
            })->toArray());

            $items = ProductVariant::select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale', 'quantity')
            ->with([
                'product:id,name,img_thumbnail,slug', 
                'size:id,name',
                'color:id,name'
            ])
            ->whereRaw("(product_id, size_id, color_id) IN ($cart_array)")->get()
            ->map(function($item) use ($cartMap){

                $sizeInfo = optional($item->size);
                $colorInfo = optional($item->color);
                $productInfo = optional($item->product);
                $key = implode('_', [$productInfo->id, $sizeInfo->id, $colorInfo->id]);
                $quantity = $cartMap[$key];
                return [
                    'product_id' => $productInfo->id,
                    'product_name' => $productInfo->name,
                    'img_thumbnail' => $productInfo->img_thumbnail,
                    'slug' => $productInfo->slug,
                    'price' => $item->price,
                    'price_sale' => $item->price_sale,
                    'size_id' => $sizeInfo->id,
                    'size_name' => $sizeInfo->name,
                    'color_id' => $colorInfo->id,
                    'color_name' => $colorInfo->name,
                    'quantity' => $quantity,
                    'total_price' => $quantity * $item->price,
                ];
            });

            // /Tổng giá của đơn hàng
            $total = $items->reduce(function ($total_price, $item) {
                return $total_price + $item['total_price'];
            }, 0);

            return response()->json([
                'success' => true,
                'total_price' => $total,
                'items' => $items
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
