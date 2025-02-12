<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    const PATH_IMAGE = 'img_thumbnail';
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

    public function index(Request $request)
    {
        try {
            $query = Product::query();
            $column = ['id', 'img_thumbnail', 'name'];
            $order_column = $request->input("order_column", 0);
            $order_dir = $request->input("order_dir", 'asc');
            
            $products = $query->where('name', "LIKE", "%$request->search%")->orderBy($column[$order_column],$order_dir )
            ->offset($request->start)->limit($request->length)->get();

            $totalRecords = $query->count();
            $filteredRecords = $totalRecords;

            return response()->json([
                "success" => true,
                "draw" => $request->draw,
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data" => $products
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $img_thumbnail = Storage::put(self::PATH_IMAGE, $data['img_thumbnail']);
            $data['img_thumbnail'] = $img_thumbnail;
            $product = Product::create($data);

            if ($data['variants']) {
                $variants_parseJson  = json_decode($data['variants']);
                $productVariants = [];
                foreach ($variants_parseJson as $variant) {
                    $productVariants[] = [
                        'product_id' => $product->id,
                        'size_id' => $variant->size_id,
                        'color_id' => $variant->color_id,
                        'price' => $variant->price,
                        'quantity' => $variant->quantity
                    ];
                }
                ProductVariant::insert($productVariants);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Thêm mới sản phẩm thành công!'
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with([
                'category.parent',
                'variants.size',
                'variants.color'
            ])->where('id', $id)->first();
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => "Sản phẩm này không tồn tại"
                ], 404);
            }
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::where('id', $id)->first();
            $product->delete();
            ProductVariant::where('product_id', $product->id)->delete();

            if ($product->img_thumbnail && Storage::exists($product->img_thumbnail)) {
                Storage::delete($product->img_thumbnail);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Xoá sản phẩm thành công!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->handleErrorNotDefine($th);
        }
    }
}
