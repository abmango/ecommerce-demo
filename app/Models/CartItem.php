<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Esto sólo vale para Fujifoods. Para cualquier otro caso, eliminarlo
    public function finalPriceFor(CartItem $item, User $user): float
    {
        $price = $item->product->price;

        // Reglas por tipo de cliente
        if (str_contains($item->product->type, 'Masas artesanales')) {
            switch ($user->role) {
                case 'restaurant':
                    //acá no hace absolutamente nada ya que es el precio base
                    break;
                default:
                    $price *= 1.20;
                    break;
            }
        }

        // Reglas por cantidad para galletitas
        if ($item->quantity >= 30 && str_contains($item->product->name, 'Sembei')) {
            $price *= 0.70;
        }

        return round($price, 2);
    }

}