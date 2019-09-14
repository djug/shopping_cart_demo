# Removed From Cart Before Checkout (demo)


This is a demo of how to implement a mechanism to "log" products removed from a cart before checkout. this could be useful in case the marketing department wants to re-target some users by sending them coupons for these products.

## How to install

- Install the dependencies: `composer install`
- Create a new database and create/update your `.env` file accordingly. You can copy the `.env.example` file (`cp .env.example .env`) 
- run the migrations `php artisan migrate` and the seeders php artisan `db:seed`
- generate an encryption key `php artisan key:generate` 
- Lunch the built-in PHP webserver `php artisan serve` 
- The demo will be available at `http://127.0.0.1:8000/`

## Directory Permissions
Directories within the storage and the `bootstrap/cache` directories should be writable by your web server or the application might not run correctly.

## Presentation
This demo application is kept to its core minimum. After seeding the test data we can use its 3 API endpoints:

- `GET /cart`: to get the current user cart content.
- `POST /cart/{product}` : to add a product to the cart.
- `DELETE /cart/{product}`: to remove a product from a cart.

Before sending any request, we need first to get the API token of the user we want to target first (from the DB). after that we query the endpoints like this:

### Getting the current cart:

```BASH
curl -X GET \
  http://127.0.0.1:8000/api/cart \
  -H 'Authorization: Bearer USER_AUTH_TOKEN_HERE' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -H 'Host: 127.0.0.1:8000'
```

### Adding a product to the cart:

```BASH
curl -X POST \
  http://127.0.0.1:8000/api/cart/1 \
  -H 'Authorization: Bearer USER_AUTH_TOKEN_HERE' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -H 'Host: 127.0.0.1:8000'
```

### Removing a product from the cart:

```BASH
curl -X DELETE \
  http://127.0.0.1:8000/api/cart/1 \
  -H 'Authorization: Bearer USER_AUTH_TOKEN_HERE' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -H 'Host: 127.0.0.1:8000'
```


## How does this work:
Our goal is to track products that are removed from the cart, so all that needs to be done is to add a record to the `removed_before_checkout` table each time a user removes a product from her cart.

Since this operation will hit the DB, we might notice a performance drop. So in order to avoid that, we just need to fire an event called `ProductRemovedFromCart` whenever we remove a product.

We have an event listener called `LogProductRemovedFromCart` that will pick this event up, and process it (i.e add it to the table mentioned above). but this operation will be done asynchronously, since we are queuing it in the `removed-from-cart` queue before it gets processed by the queue worker 


**Note**: you'd need to change the value of `QUEUE_CONNECTION` in `.env` (from `sync` to `redis` for example) in order to process this request asynchronously.


## Getting the list of removed products

This could be done via the `ProductRepository` class, which allows us to either get all the products removed by a particular user, or all the users who removed a particular product.

## Test
we have one test class called `CartControllerTest`, which makes sure that adding to and removing from carts (including logging the removals) work as expected.

You can run the tests by executing `vendor/bin/phpunit` in the root directory of the project.


## Improvements:
Things we can do to improve the code:
- do not log a product if it is not already in the cart. currently the code doesn't check that out. this might not be necessary, but it might be useful in some cases.
- if a product was already removed, updated the number of removal instead of adding a new record. This might be useful to determine which products interested the users the mosts
