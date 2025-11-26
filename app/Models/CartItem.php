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
    public function finalPriceFor(CartItem $item, ?User $user, int $totalSembei = null): float
    {
        $price = $item->product->price;

        // Reglas por tipo de cliente (solo si existe user)
        if (str_contains($item->product->type, 'Masas artesanales')) {
            if ($user) {
                switch ($user->role) {
                    case 'restaurant':
                        // precio base
                        break;
                    default:
                        $price *= 1.20;
                        break;
                }
            } else {
                $price *= 1.20; // también se aplica para no logueados
            }
        }

        // Reglas por cantidad de productos Sembei (aplica a todos: logueados y no logueados)
        if ($totalSembei !== null && $totalSembei >= 30 && str_contains($item->product->name, 'Sembei')) {
            $price *= 0.70;
        }

        return round($price, 2);
    }

}