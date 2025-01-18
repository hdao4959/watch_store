<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function handleErrorNotDefine($th){
        return response()->json(
            [
                'success' => false,
                'message' => 'Có lỗi xảy ra! ' . $th
            ],500
            );
    }

    public function orderHistory(){
        try {
            $user_id = request()->user()->id;
            $orders = Order::where('id_user', $user_id)->get();
            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    public function show(string $id){
        try {
            $user_id = request()->user()->id;
            $order = Order::with('order_items')->where([
                'id' =>  (int) $id,
                'id_user' => $user_id
            ])->first();

            return response()->json([
                'success' => true,
                'order' => $order
            ]);

        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
