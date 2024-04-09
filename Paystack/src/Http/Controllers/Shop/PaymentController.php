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

    /**
     * Redirects to the paystack.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        $cart = Cart::getCart();
        return paystack()
            ->getAuthorizationUrl([
                'amount' => $cart->grand_total * 100,
                'email' => $cart->billing_address->email,
                //"currency" => (request()->currency != ""  ? request()->currency : "NGN"),
                'callback_url' => route('paystack.success'),
                'metadata' => ["cancel_action" => route('paystack.cancel')]
            ])
            ->redirectNow();
    }

    /**
     * Cancel payment from paystack.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->flash('error', trans('shop::app.checkout.cart.paystack-payment-canceled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Verify payment from paystack and complete order.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        if (paystack()->isValid(request()->query('reference'))) {
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());
            Cart::deActivateCart();
            //session()->flash('order', $order);
            return redirect()->route('shop.checkout.onepage.success');
        }
    }

    /**
     * trigger paystack popup.
     *
     * @return \Illuminate\View\View
     */
    public function popup()
    {
        return view('paystack::paystack-popup');
    }
}
