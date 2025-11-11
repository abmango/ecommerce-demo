<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'invoice_path',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price'); // Incluye las columnas adicionales en la tabla pivote
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
