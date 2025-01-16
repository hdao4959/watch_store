<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'address',
        'type_pay',
        'id_user',
        'amount',
        'status'
    ];

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }
}
