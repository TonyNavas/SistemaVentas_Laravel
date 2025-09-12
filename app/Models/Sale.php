<?php

namespace App\Models;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    // No lo estoy usando
    public function items(){
        return $this->belongsToMany(Item::class)->withPivot(['qty', 'fecha']);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Opcional: acceder a todos los detalles de las órdenes a través de la venta
    public function orderDetails()
    {
        return $this->hasManyThrough(OrderDetail::class, Order::class);
    }

        // Relación con la mesa
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

}
