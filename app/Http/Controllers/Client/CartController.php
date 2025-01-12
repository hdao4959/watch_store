<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CheckoutRequest;
use App\Http\Requests\Cart\GetItemsCartRequest;
use App\Http\Requests\Cart\OrderRequest;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


            $variants = ProductVariant::select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale')
                ->with([
                    'product:id,name,img_thumbnail,slug',
                    'color:id,name',
                    'size:id,name'
                ])
                ->whereRaw("(product_id, size_id, color_id) IN ($cartArraySql)")
                ->get()
                ->map(function ($variant) {
                    $infoProduct = optional($variant->product);
                    $infoColor = optional($variant->color);
                    $infoSize = optional($variant->size);
                    return [
                        'product_id' => $infoProduct->id,
                        'product_name' => $infoProduct->name,
                        'img_thumbnail' => $infoProduct->img_thumbnail,
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

    //     try {
    //         $data = $request->validated();
    //         $cart = collect($data['cart']);
    //         $cartMap = $cart->mapWithKeys(function($item){
    //             return [implode('_', [$item['id'], $item['size'], $item['color']])  => $item['quantity']];
    //         });

    //         $cart_array = implode(',', $cart->map(function($item){
    //             return "({$item['id']}, {$item['size']}, {$item['color']})";
    //         })->toArray());

    //         $items = ProductVariant::select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale', 'quantity')
    //         ->with([
    //             'product:id,name,img_thumbnail,slug', 
    //             'size:id,name',
    //             'color:id,name'
    //         ])
    //         ->whereRaw("(product_id, size_id, color_id) IN ($cart_array)")->get()
    //         ->map(function($item) use ($cartMap){

    //             $sizeInfo = optional($item->size);
    //             $colorInfo = optional($item->color);
    //             $productInfo = optional($item->product);
    //             $key = implode('_', [$productInfo->id, $sizeInfo->id, $colorInfo->id]);
    //             $quantity = $cartMap[$key];
    //             return [
    //                 'product_id' => $productInfo->id,
    //                 'product_name' => $productInfo->name,
    //                 'img_thumbnail' => $productInfo->img_thumbnail,
    //                 'slug' => $productInfo->slug,
    //                 'price' => $item->price,
    //                 'price_sale' => $item->price_sale,
    //                 'size_id' => $sizeInfo->id,
    //                 'size_name' => $sizeInfo->name,
    //                 'color_id' => $colorInfo->id,
    //                 'color_name' => $colorInfo->name,
    //                 'quantity' => $quantity,
    //                 'total_price' => $quantity * $item->price,
    //             ];
    //         });

    //         // /Tổng giá của đơn hàng
    //         $total = $items->reduce(function ($total_price, $item) {
    //             return $total_price + $item['total_price'];
    //         }, 0);

    //         return response()->json([
    //             'success' => true,
    //             'total_price' => $total,
    //             'items' => $items
    //         ]);
    //     } catch (\Throwable $th) {
    //         return $this->handleErrorNotDefine($th);
    //     }
    // }

    public function checkout(CheckoutRequest $request)
    {
        try {
            $data = $request->validated();
            $cart = collect($data['cart']);

            $cartMap = $cart->mapWithKeys(function ($item) {
                return [implode('_', [$item['id'], $item['size'], $item['color']])  => $item['quantity']];
            });


            $cart_array = implode(',', $cart->map(function ($item) {
                return "({$item['id']}, {$item['size']}, {$item['color']})";
            })->toArray());

            $items = ProductVariant::select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale', 'quantity')
                ->with([
                    'product:id,name,img_thumbnail,slug',
                    'size:id,name',
                    'color:id,name'
                ])
                ->whereRaw("(product_id, size_id, color_id) IN ($cart_array)")->get()
                ->map(function ($item) use ($cartMap) {

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

    public function order(OrderRequest $request)
    {
        try {
            $data = $request->validated();
            $cart = collect($data['cart']);

            $cartMap = $cart->mapWithKeys(function ($item) {
                return [implode('_', [$item['id'], $item['size'], $item['color']]) => $item['quantity']];
            });

            $cart_array = implode(',', $cart->map(function ($item) {
                return "({$item['id']},{$item['size']},{$item['color']})";
            })->toArray());

            $items = ProductVariant::select('id', 'product_id', 'size_id', 'color_id', 'price', 'price_sale', 'quantity')
                ->with([
                    'product:id,name,img_thumbnail,slug',
                    'size:id,name',
                    'color:id,name'
                ])
                ->whereRaw("(product_id, size_id, color_id) IN ($cart_array)")->get()
                ->map(function ($item) use ($cartMap) {

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
            $total_price = $items->reduce(function ($total_price, $item) {
                return $total_price + $item['total_price'];
            }, 0);
            $order_data = [
                'full_name' => $data['full_name'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'type_pay' => (string) $data['type_pay'],
                'id_user' => (string) $data['id_user'],
            ];

            DB::beginTransaction();
            $order = Order::create($order_data);

            $order_items_data = [];
            $now = now();
            foreach($items as $item){
                array_push($order_items_data, [
                    'product_name' => $item['product_name'],
                    'img_thumbnail' => $item['img_thumbnail'],
                    'size' => $item['size_name'],
                    'color' => $item['color_name'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'order_id' => $order->id,
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }

            OrderItem::insert($order_items_data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Đăt hàng thành công!"
            ]);

            // $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            // $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
            // $vnp_TmnCode = "4ZKFHMA9"; //Mã website tại VNPAY 
            // $vnp_HashSecret = "MXVUVZFZ8IUCCIS0N3S0KPYTY6VBRNBS"; //Chuỗi bí mật

            // $vnp_TxnRef = $_POST['order_id']; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            // $vnp_OrderInfo = $_POST['order_desc'];
            // $vnp_OrderType = $_POST['order_type'];
            // $vnp_Amount = $total_price * 100;
            // $vnp_Locale = $_POST['language'];
            // $vnp_BankCode = $_POST['bank_code'];
            // $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            // //Add Params of 2.0.1 Version
            // $vnp_ExpireDate = $_POST['txtexpire'];
            // //Billing
            // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
            // $vnp_Bill_Email = $_POST['txt_billing_email'];
            // $fullName = trim($_POST['txt_billing_fullname']);
            // if (isset($fullName) && trim($fullName) != '') {
            //     $name = explode(' ', $fullName);
            //     $vnp_Bill_FirstName = array_shift($name);
            //     $vnp_Bill_LastName = array_pop($name);
            // }
            // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
            // $vnp_Bill_City = $_POST['txt_bill_city'];
            // $vnp_Bill_Country = $_POST['txt_bill_country'];
            // $vnp_Bill_State = $_POST['txt_bill_state'];
            // // Invoice
            // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
            // $vnp_Inv_Email = $_POST['txt_inv_email'];
            // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
            // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
            // $vnp_Inv_Company = $_POST['txt_inv_company'];
            // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
            // $vnp_Inv_Type = $_POST['cbo_inv_type'];
            // $inputData = array(
            //     "vnp_Version" => "2.1.0",
            //     "vnp_TmnCode" => $vnp_TmnCode,
            //     "vnp_Amount" => $vnp_Amount,
            //     "vnp_Command" => "pay",
            //     "vnp_CreateDate" => date('YmdHis'),
            //     "vnp_CurrCode" => "VND",
            //     "vnp_IpAddr" => $vnp_IpAddr,
            //     "vnp_Locale" => $vnp_Locale,
            //     "vnp_OrderInfo" => $vnp_OrderInfo,
            //     "vnp_OrderType" => $vnp_OrderType,
            //     "vnp_ReturnUrl" => $vnp_Returnurl,
            //     "vnp_TxnRef" => $vnp_TxnRef,
            //     "vnp_ExpireDate" => $vnp_ExpireDate,
            //     "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            //     "vnp_Bill_Email" => $vnp_Bill_Email,
            //     "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            //     "vnp_Bill_LastName" => $vnp_Bill_LastName,
            //     "vnp_Bill_Address" => $vnp_Bill_Address,
            //     "vnp_Bill_City" => $vnp_Bill_City,
            //     "vnp_Bill_Country" => $vnp_Bill_Country,
            //     "vnp_Inv_Phone" => $vnp_Inv_Phone,
            //     "vnp_Inv_Email" => $vnp_Inv_Email,
            //     "vnp_Inv_Customer" => $vnp_Inv_Customer,
            //     "vnp_Inv_Address" => $vnp_Inv_Address,
            //     "vnp_Inv_Company" => $vnp_Inv_Company,
            //     "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            //     "vnp_Inv_Type" => $vnp_Inv_Type
            // );

            // if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            //     $inputData['vnp_BankCode'] = $vnp_BankCode;
            // }
            // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            // }

            // //var_dump($inputData);
            // ksort($inputData);
            // $query = "";
            // $i = 0;
            // $hashdata = "";
            // foreach ($inputData as $key => $value) {
            //     if ($i == 1) {
            //         $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            //     } else {
            //         $hashdata .= urlencode($key) . "=" . urlencode($value);
            //         $i = 1;
            //     }
            //     $query .= urlencode($key) . "=" . urlencode($value) . '&';
            // }

            // $vnp_Url = $vnp_Url . "?" . $query;
            // if (isset($vnp_HashSecret)) {
            //     $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            //     $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            // }
            // $returnData = array(
            //     'code' => '00',
            //     'message' => 'success',
            //     'data' => $vnp_Url
            // );
            // if (isset($_POST['redirect'])) {
            //     header('Location: ' . $vnp_Url);
            //     die();
            // } else {
            //     echo json_encode($returnData);
            // }
            // // vui lòng tham khảo thêm tại code demo




        } catch (\Throwable $th) {
            DB::rollback();
            return $this->handleErrorNotDefine($th);
        }
    }
}
