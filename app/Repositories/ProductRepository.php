<?php
namespace App\Repositories;

use DB;
use App\User;
use App\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    /**
     * Get all the product removed from cart before checkout by a specific user
     * @param  User
     * @return Collection
     */
    public function getAllProductRemovedFromCartBeforeCheckoutForUser(User $user) : Collection
    {
        return DB::table('removed_before_checkout')->where('user_id', $user->id)->get();
    }

    /**
     * get all the user who removed a specific product from the cart before checkout
     * @param  Product
     * @return Collection
     */
    public function getAllUsersRemovedProductBeforeCheckoutForProduct(Product $product) : Collection
    {
        return DB::table('removed_before_checkout')->where('product_id', $product->id)->get();
    }
}
