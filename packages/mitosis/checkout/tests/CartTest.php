<?php


namespace Mitosis\Checkout\Tests;

use Mitosis\Checkout\Facades\Checkout;
use Mitosis\Checkout\Tests\Example\Cart;
use Mitosis\Checkout\Tests\Example\Product;
use Mitosis\Contracts\CheckoutSubject;

class CartTest extends TestCase
{
    /** @var  Product */
    protected $productEarly2018;

    /** @var  Product */
    protected $productNormal2018;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productEarly2018  = new Product(1, 'Laracon EU 2018 Ticket Early Bird', 399);
        $this->productNormal2018 = new Product(2, 'Laracon EU 2018 Ticket', 599);
    }

    /**
     * @test
     */
    public function an_arbitrary_object_implementing_checkoutsubject_can_be_set_as_cart()
    {
        $cart = new Cart();

        Checkout::setCart($cart);

        $this->assertInstanceOf(CheckoutSubject::class, Checkout::getCart());
    }

    /**
     * @test
     */
    public function checkout_total_matches_cart_total()
    {
        $cart = new Cart();

        Checkout::setCart($cart);

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(399, Checkout::total());

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(798, Checkout::total());

        $cart->addItem($this->productEarly2018, 2);
        $this->assertEquals(1596, Checkout::total());

        $cart->addItem($this->productNormal2018);
        $this->assertEquals(2195, Checkout::total());
    }

    /**
     * @test
     */
    public function checkout_can_return_products()
    {
        $cart = new Cart();

        $cart->addItem($this->productEarly2018);
        $cart->addItem($this->productNormal2018);

        Checkout::setCart($cart);

        $this->assertCount(2, Checkout::getCart()->getItems());
    }
}
