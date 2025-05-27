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
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price'); // Incluye las columnas adicionales en la tabla pivote
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function getInvoicePathAttribute($value)
    // {
    //     if ($value) {
    //         return $value; // usa lo que haya en la BD
    //     }

    //     // Verificá si el archivo existe antes de asumir que hay uno
    //     $generatedPath = "invoices/20150978387_011_00005_" . str_pad($this->id, 8, '0', STR_PAD_LEFT) . ".pdf";

    //     if (\Storage::disk('public')->exists($generatedPath)) {
    //         return $generatedPath;
    //     }

    //     return null;
    // }

}
