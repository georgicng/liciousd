<?php

namespace Gaiproject\Paystack\Http\Controllers\Shop;

use Gaiproject\Paystack\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;

class PaymentController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;
    public $paystack;

    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    public function callback()
    {
        if (paystack()->isValid(request()->query('reference'))) {
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());
            Cart::deActivateCart();
            session()->flash('order', $order);
            return redirect()->route('shop.checkout.success');
        }
    }
}
