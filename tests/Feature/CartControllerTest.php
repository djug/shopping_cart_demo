<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

class CartControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function a_user_can_add_product_to_cart()
    {
        $user = $this->getNewUserWithEmptyCart();
        $product = $this->getNewProduct();
        $nbProductInCartBefore = count($user->cart->products_list);

        $response = $this
            ->apiAuth($user)
            ->post(route('cart_add_product', ['product' => $product]));

        $response->assertSuccessful();
        $products = $user->refresh()->cart->products_list;

        $nbProductInCartAfter = count($products);

        $this->assertEquals($nbProductInCartBefore + 1, $nbProductInCartAfter);

        $addedProduct = end($products);
        $this->assertEquals($product->id, $addedProduct['id']);
    }


    /**
     * @test
     */
    public function a_user_can_remove_a_product_from_cart()
    {
        $user = $this->getNewUserWithNonEmptyCart();

        $nbProductInCartBefore = count($user->cart->products_list);

        $nbRemovedProductsBefore = DB::table('removed_before_checkout')->count();
        $cartProductsIDs = array_map(function ($product) {
            return $product['id'];
        }, $user->cart->products_list);

        $randomProductID = $cartProductsIDs[array_rand($cartProductsIDs)];
        $response = $this
            ->apiAuth($user)
            ->delete(route('cart_remove_product', ['product' => $randomProductID]));

        $response->assertSuccessful();

        $products = $user->refresh()->cart->products_list;

        $nbProductInCartAfter = count($products);

        $this->assertEquals($nbProductInCartBefore - 1, $nbProductInCartAfter);

        $nbRemovedProductsAfter= DB::table('removed_before_checkout')->count();

        $this->assertEquals($nbRemovedProductsBefore + 1, $nbRemovedProductsAfter);

        $lastProductRemovedFromCart = DB::table('removed_before_checkout')->orderBy('id', 'desc')->first();

        $this->assertEquals($lastProductRemovedFromCart->product_id, $randomProductID);
    }
}
