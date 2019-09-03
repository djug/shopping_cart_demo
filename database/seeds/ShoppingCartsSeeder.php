<?php

use Illuminate\Database\Seeder;
use App\ShoppingCart;
use App\Product;
class ShoppingCartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carts = ShoppingCart::get();
        $products = Product::get();

        $carts->each(function ($cart) use ($products) {
            $randomProducts = $products->random(5);
            $randomProducts->each(function ($product) use ($cart) {
                $cart->products()->save($product, ['quantity' => rand(1, 10)]);
            });
        });
    }
}
