<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\ShoppingCart;
use App\Product;

class ProductRemovedFromCart
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cart;
    public $product;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ShoppingCart $cart, Product $product)
    {
        $this->cart = $cart;
        $this->product = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
