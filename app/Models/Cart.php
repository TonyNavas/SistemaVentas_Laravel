<?php

namespace App\Models;

class Cart
{
    public static function add(Product $product){

        $userID = auth()->user()->id;

        Cart::session($userID)->add(array(
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->precio_venta,
            'quantity' => 1,
            'attributes' => array(),
            'asociatedModel' => $product,
        ));

        dump('item agregado');
    }
}
