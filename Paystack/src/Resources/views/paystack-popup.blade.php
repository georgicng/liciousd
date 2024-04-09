@if (
request()->routeIs('paystack.popup')
&& (bool) core()->getConfigData('sales.payment_methods.paystack_popup.active')
)
@php $paystack = app('Gaiproject\Paystack\Payment\PaystackPopup') @endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">

<head>
    <title> @lang('shop::app.checkout.onepage.index.checkout')</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="currency-code" content="{{ core()->getCurrentCurrencyCode() }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')" />

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')" />

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap">

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>
</head>

<body>

    <div id="app">
        <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
        </div>
    </div>

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        function payWithPaystack() {
            var fields = @json($paystack->getFormFields());
            var handler = PaystackPop.setup({
                ...fields,
                onClose: function() {
                    window.location.href = "{{ route('paystack.cancel') }}";
                },
                callback: function(response) {
                    window.location.href = `{{ route('paystack.success') }}?reference=${response.reference}`;
                }
            });

            handler.openIframe();
        }
        payWithPaystack();
    </script>
</body>
</html>
@endif
