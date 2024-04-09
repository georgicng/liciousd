@if (
    request()->routeIs('paystck.popup')
    && (bool) core()->getConfigData('sales.payment_methods.paystack_popup.active')
)
    @php $paystack = app('Gaiproject\Paystack\Payment\PaystackPopup') @endphp

    {{-- SEO Meta Content --}}
    @push('meta')
        <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>

        <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
    @endPush

    <x-shop::layouts
        :has-header="false"
        :has-feature="false"
        :has-footer="false"
    >
        {{-- Page Title --}}
        <x-slot:title>
            @lang('shop::app.checkout.onepage.index.checkout')
        </x-slot>

        {{-- Page Header --}}
        <div class="lex flex-wrap">
            <div class="w-full flex justify-between px-[60px] py-[17px] border border-t-0 border-b-[1px] border-l-0 border-r-0 max-lg:px-[30px] max-sm:px-[15px]">
                <div class="flex items-center gap-x-[54px] max-[1180px]:gap-x-[35px]">
                    <a
                        href="{{ route('shop.home.index') }}"
                        class="flex min-h-[30px]"
                        aria-label="Bagisto "
                    >
                        <img
                            src="{{ bagisto_asset('images/logo.svg') }}"
                            alt="Bagisto "
                            width="131"
                            height="29"
                        >
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
            {{-- Breadcrumbs --}}
            <x-shop::breadcrumbs name="checkout"></x-shop::breadcrumbs>

            {{-- Shimmer Effect --}}
            <x-shop::shimmer.checkout.onepage/>
        </div>

        @pushOnce('scripts')
            <script src="https://js.paystack.co/v1/inline.js"></script>
            <script>
                function payWithPaystack() {
                    var fields = @json($paystack->getFormFields());
                    var handler = PaystackPop.setup({
                        ...fields,
                        onClose: function() {
                            window.location.href = {{ route('paystack.cancel') }};
                        },
                        callback: function(response) {
                            var route = {{ route('paystack.success') }};
                            window.location.href = `${route}?${response.reference}`;
                        }
                    });

                    handler.openIframe();
                }
                payWithPaystack();
            </script>
        @endPushOnce
    </x-shop::layouts>
@endif

