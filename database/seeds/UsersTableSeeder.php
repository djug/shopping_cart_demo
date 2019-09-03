<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) {
               $user->shoppingCart()->save(App\ShoppingCart::create(['user_id' => $user->id]));
        });
    }
}
