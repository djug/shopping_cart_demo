<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShoppingCart;
use App\Product;
use Auth;
use App\Providers\ProductRemovedFromCart;

class CartController extends Controller
{


    public function get()
    {
        return $this->getCurrentUserCart();
    }

    public function add(Product $product)
    {
        $cart =  $this->getCurrentUserCart();

        $cart->products()->attach($product->id);

        $response = [
            'cart' => $cart->id,
            'product' => $product->id
        ];
        return response($response, 201);
    }

    public function remove(Product $product)
    {
        $cart =  $this->getCurrentUserCart();

        $cart->products()->detach($product->id);

        event(new ProductRemovedFromCart($cart, $product));

        return response(null, 204);
    }

    protected function getCurrentUserCart()
    {
        return Auth::user()->cart;
    }
}
