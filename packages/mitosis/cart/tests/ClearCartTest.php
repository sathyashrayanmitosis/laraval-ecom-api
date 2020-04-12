<?php


namespace Mitosis\Cart\Tests;

use Mitosis\Cart\Facades\Cart;
use Mitosis\Cart\Models\CartItemProxy;
use Mitosis\Cart\Tests\Dummies\Product;

class ClearCartTest extends TestCase
{
    /** @var  Product */
    protected $product5;

    /** @var  Product */
    protected $product6;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product5 = Product::create([
            'name'  => 'Spicy Ketchup',
            'price' => 1.29
        ]);

        $this->product6 = Product::create([
            'name'  => 'Curry Sauce',
            'price' => 1.09
        ]);
    }

    /**
     * @test
     */
    public function clearing_items_results_in_an_empty_cart()
    {
        Cart::addItem($this->product5);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->items);

        Cart::clear();

        $this->assertEquals(0, Cart::itemCount());
        $this->assertCount(0, Cart::model()->items);
        $this->assertTrue(Cart::isEmpty());
    }

    /**
     * @test
     */
    public function and_yes_of_course_multiple_items_get_all_deleted()
    {
        Cart::addItem($this->product5, 3);
        Cart::addItem($this->product6, 4);

        $this->assertEquals(7, Cart::itemCount());
        $this->assertCount(2, Cart::model()->items);

        Cart::clear();

        $this->assertEquals(0, Cart::itemCount());
        $this->assertCount(0, Cart::model()->items);
        $this->assertTrue(Cart::isEmpty());
    }

    /**
     * @test
     */
    public function clear_cart_removes_items_from_the_db()
    {
        Cart::addItem($this->product5);
        Cart::addItem($this->product6);

        $cartId = Cart::model()->id;
        $this->assertCount(2, CartItemProxy::ofCart($cartId)->get());

        Cart::clear();

        $this->assertCount(0, CartItemProxy::ofCart($cartId)->get());
    }
}
