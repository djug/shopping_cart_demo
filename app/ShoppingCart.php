<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{

    protected $appends = ['products_list'];
    protected $hidden = ['products'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_product', 'cart_id', 'product_id')->withPivot('quantity')->withTimestamps();
    }

    public function getProductsListAttribute()
    {
        $productsWithQuantities = [];

        $this->products->each(function ($product) use (&$productsWithQuantities) {

            $productsWithQuantities [$product->id] = ['name' => $product->name, 'quantity' => $product->pivot->quantity];
        });
        return $productsWithQuantities;
    }
}
