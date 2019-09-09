<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;
use App\Product;
use App\ShoppingCart;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function apiAuth($user)
    {
        return $this->withHeaders(['Authorization' => "Bearer " . $user->api_token]);
    }

    public function getNewUserWithEmptyCart()
    {
        $user = factory(User::class)->create();
        $user->cart()->save(ShoppingCart::create(['user_id' => $user->id]));

        return $user;
    }

    public function getNewUserWithNonEmptyCart()
    {
        $user = factory(User::class)->create();
        $cart = ShoppingCart::create(['user_id' => $user->id]);

        $user->cart()->save($cart);

        $products = factory(Product::class, 5)->create();

        $products->each(function ($product) use ($cart) {
            $cart->products()->save($product, ['quantity' => rand(1, 10)]);
        });

        return $user;
    }





    public function getNewProduct()
    {
        return factory(Product::class)->create();
    }
}
