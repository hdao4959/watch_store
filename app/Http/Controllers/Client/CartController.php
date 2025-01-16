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
use App\Models\Transaction;
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
                'amount' => $total_price
            ];

            DB::beginTransaction();
            $order = Order::create($order_data);

            $order_items_data = [];
            $now = now();
            foreach ($items as $item) {
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

            // Trường hợp thanh toán khi nhận hàng
           if($data['type_pay'] == 0){
                return response()->json([
                'success' => true,
                'url_redirect' => env('APP_URL') . '/account'
            ]);
           }
            $vnp_Url = env("VNP_URL");
            $vnp_Returnurl = env("VNP_RETURN_URL");
            $vnp_TmnCode = env("VNP_TMN_CODE");
            $vnp_HashSecret = env("VNP_HASH_SECRET");

            $vnp_TxnRef = $order->id; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = "Thanh toan don hang $vnp_TxnRef";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $total_price * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $vnp_IpAddr = request()->ip();
            $vnp_ExpireDate = date("YmdHis", strtotime("+15 minutes"));
            //Billing

            // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
            $vnp_Bill_Mobile = $data['phone_number'];
            // $vnp_Bill_Email = $_POST['txt_billing_email'] ?? '';
            // $vnp_Bill_Email =$data['email'];
            // $fullName = trim($_POST['txt_billing_fullname']);
            $fullName = trim($data['full_name']);
            if (isset($fullName) && trim($fullName) != '') {
                $name = explode(' ', $fullName);
                $vnp_Bill_FirstName = array_shift($name);
                $vnp_Bill_LastName = array_pop($name);
            }
            // $vnp_Bill_Address=$_POST['txt_inv_addr1'];
            // $vnp_Bill_Address = $data['address'];
            $vnp_Bill_Address = $data['address'];
            // $vnp_Bill_City=$_POST['txt_bill_city'];
            // $vnp_Bill_City = $data['address'];
            // $vnp_Bill_City = $data['address'];
            // $vnp_Bill_Country = $_POST['txt_bill_country'];
            // $vnp_Bill_Country = 'VN';
            // $vnp_Bill_State = $_POST['txt_bill_state'];
            $vnp_Bill_State = 'Pending';
            // Invoice
            // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
            $vnp_Inv_Phone = $vnp_Bill_Mobile;
            // $vnp_Inv_Email = $_POST['txt_inv_email'];
            // $vnp_Inv_Email = $vnp_Bill_Email;
            // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
            $vnp_Inv_Customer = $fullName;
            $vnp_Inv_Address = $vnp_Bill_Address;
            // $vnp_Inv_Company = $_POST['txt_inv_company'] ?? '';
            // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'] ?? '';
            // $vnp_Inv_Type = $_POST['cbo_inv_type'] ?? '';
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_Command" => "pay",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_ExpireDate" => $vnp_ExpireDate,
                "vnp_TxnRef" => $vnp_TxnRef,


                "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
                // "vnp_Bill_Email" => $vnp_Bill_Email,
                "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
                "vnp_Bill_LastName" => $vnp_Bill_LastName,
                "vnp_Bill_Address" => $vnp_Bill_Address,
                // "vnp_Bill_City" => $vnp_Bill_City,
                // "vnp_Bill_Country" => $vnp_Bill_Country,
                "vnp_Inv_Phone" => $vnp_Inv_Phone,
                // "vnp_Inv_Email" => $vnp_Inv_Email,
                "vnp_Inv_Customer" => $vnp_Inv_Customer,
                "vnp_Inv_Address" => $vnp_Inv_Address,
                // "vnp_Inv_Company" => $vnp_Inv_Company,
                // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
                // "vnp_Inv_Type" => $vnp_Inv_Type, 

            );
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }
            //var_dump($inputData);
            ksort($inputData);

            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            $returnData = array(
                'code' => '00',
                'message' => 'success',
                'url_redirect' => $vnp_Url
            );
            return response()->json($returnData);
        
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->handleErrorNotDefine($th);
        }
    }

    public function handleIPN()
    {
        $inputData = array();
        $returnData = array();

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $vnp_HashSecret = env("VNP_HASH_SECRET"); //Chuỗi bí mật
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi

        $Status = 'canceled'; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo  URL thanh toán.
        $orderId = $inputData['vnp_TxnRef'];

        try {
            //Check Orderid    
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {

                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId            
                $order = Order::find($orderId);
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch

                if ($order != NULL) {
                    if ($order["amount"] == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền  kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
                    {
                        if ($order["status"] != NULL && $order["status"] == 'pending') {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $Status = 'completed'; // Trạng thái thanh toán thành công
                            } else {
                                $Status = 'failed'; // Trạng thái thanh toán thất bại / lỗi
                            }
                            // DB::table('tabletest')->insert([
                            //     'a' => $vnpTranId,
                            //     'b' => $orderId,
                            //     'c' => $vnp_Amount,
                            //     'd' => $vnp_BankCode
                            // ]);
                            


                            //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
                            DB::beginTransaction();
                            Transaction::create([
                                'tran_id' => $vnpTranId,
                                'order_id' => $orderId,
                                'bank_code' => $vnp_BankCode,
                                'amount' => $vnp_Amount,
                            ]);

                            $order->update([
                                'status' => $Status
                            ]);
                            DB::commit();
                            //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công                
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (\Exception $e) {
            DB::rollback();
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        //Trả lại VNPAY theo định dạng JSON
        echo json_encode($returnData);
    }
}
