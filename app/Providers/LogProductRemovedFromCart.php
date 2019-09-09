<?php

namespace App\Providers;

use App\Providers\ProductRemovedFromCart;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Carbon\Carbon;

class LogProductRemovedFromCart implements ShouldQueue
{
    public $queue = 'removed-from-cart';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductRemovedFromCart  $event
     * @return void
     */
    public function handle(ProductRemovedFromCart $event)
    {
        $cart = $event->cart;
        $user = $cart->user;
        $product = $event->product;

        DB::table('removed_before_checkout')->insert([
            'cart_id' => $event->cart->id,
            'user_id' => $event->cart->user->id,
            'product_id' => $event->product->id,
            'removed_at' =>  Carbon::now(),
        ]);
    }
}
