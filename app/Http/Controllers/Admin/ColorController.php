<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Color\StoreColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function index()
    {
        try {
            $colors = Color::paginate(10);
            return response()->json([
                'success' => true,
                'colors' => $colors
            ], 200);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColorRequest $request)
    {
        try {
            $data = $request->validated();
            Color::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thêm màu sắc thành công!'
            ], 200);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        try {
            $colorFind = Color::where('id', $color->id)->first();

            if (!$colorFind) {
                return response()->json([
                    'success' => false,
                    'message' => 'Màu này không tồn tại!'
                ], 404);
            }
            $colorFind->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xoá màu sắc thành công!'
            ], 200);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }
}
