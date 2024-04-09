@if (
    request()->routeIs('shop.checkout.onepage.index')
    && (bool) core()->getConfigData('sales.payment_methods.paystack_popup.active')
)
    @pushOnce('scripts')
        <script src="https://js.paystack.co/v1/inline.js"></script>
    @endPushOnce
@endif
