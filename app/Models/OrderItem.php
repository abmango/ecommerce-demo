<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /*public function orderItems()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price'); // Incluye las columnas adicionales en la tabla pivote
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }*/
}
