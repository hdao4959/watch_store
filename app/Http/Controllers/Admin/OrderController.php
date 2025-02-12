<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
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


    public function index(Request $request)
    {
        try {
            $query = Order::query();
            $totalOrder = $query->count();
            $column = ['id', 'full_name', 'phone_number', 'address', 'status'];
            $order_column = $request->input('order_column', 0);
            $order_dir = $request->input('order_dir', 'asc');

            $orders = $query->where('full_name', "LIKE", "%$request->search%")
            ->offset($request->start)->limit($request->length)
            ->orderBy($column[$order_column], $order_dir)->get();
            
            return response()->json([
                'success' => true,
                'draw' => (int) $request->draw,
                'recordTotal' => $totalOrder,
                'recordFiltered' => (int) $request->length,
                'data' => $orders   
            ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $order = Order::with('order_items')->find($id);

            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        } catch (\Throwable $th) {
            return $this->handleErrorNotDefine($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
