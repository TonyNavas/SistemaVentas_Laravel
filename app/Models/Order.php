<?php

namespace App\Models;

use App\Models\User;
use App\Models\Table;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
        // Relación con el mesero/usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la cabaña/mesa
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Relación con los detalles del pedido
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

        // Una orden pertenece a una venta
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
