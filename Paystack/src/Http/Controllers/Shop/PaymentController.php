<?php

namespace Gaiproject\Paystack\Http\Controllers\Shop;

use Gaiproject\Paystack\Http\Controllers\Controller;
use Gaiproject\Paystack\Payment\Paystack;
use Illuminate\Support\Facades\Redirect;
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
        OrderRepository $orderRepository,
        Paystack $paystack
    ) {
        $this->paystack = $paystack;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Redirects to the paystack.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        return view('paystack::paystack-redirect');
    }

    public function pay()
    {
        $result = $this->paystack->makePaymentRequest();

        if (!$result['status']) {
            Redirect::to(route('shop.checkout.cart.index'));
        }

        return Redirect::to($result['data']['authorization_url']);
    }

    public function callback()
    {
        $result = $this->paystack->verifyPayment(request()->query('trxref'));

        if ($result['data']['status']) {
            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            Cart::deActivateCart();

            session()->flash('order', $order);

            return redirect()->route('shop.checkout.success');
        }
    }
}
