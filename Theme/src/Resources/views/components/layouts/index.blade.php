@props([
'hasHeader' => true,
'hasFeature' => true,
'hasFooter' => true,
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">

<head>
    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="currency-code" content="{{ core()->getCurrentCurrencyCode() }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    @stack('meta')

    <link
        rel="icon"
        sizes="16x16"
        href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.png') }}" />

    <!-- Icon CSS -->
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/remixicon.css') }}">

    <!-- Vendor -->
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/animate.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/range-slider.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/jquery.slick.css') }}">
    <link rel="stylesheet" href="{{ bagisto_asset('css/vendor/slick-theme.css') }}">

    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ bagisto_asset('css/style.css') }}">

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    {!! view_render_event('bagisto.shop.layout.head') !!}
</head>

<body>
    {!! view_render_event('bagisto.shop.layout.body.before') !!}
    <div id="app">
        <!-- Flash Message Blade Component -->
        <x-licious::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-licious::modal.confirm />

        <!-- Page Header Blade Component -->
        @if ($hasHeader)
        <x-licious::layouts.header />
        @endif

        {!! view_render_event('bagisto.shop.layout.content.before') !!}

        <!-- Page Content Blade Component -->
        {{ $slot }}

        {!! view_render_event('bagisto.shop.layout.content.after') !!}

        <!-- Page Services Blade Component -->
        @if ($hasFeature)
        <x-licious::layouts.services />
        @endif

        <!-- Page Footer Blade Component -->
        @if ($hasFooter)
        <x-licious::layouts.footer />
        @endif
    </div>

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    <!-- Tab to top -->
    <a href="#Top" class="back-to-top result-placeholder h-[38px] w-[38px] hidden fixed right-[15px] bottom-[15px] z-[10] cursor-pointer rounded-[20px] bg-[#fff] text-[#64b496] border-[1px] border-solid border-[#64b496] text-center text-[22px] leading-[1.6] hover:transition-all hover:duration-[0.3s] hover:ease-in-out">
        <i class="ri-arrow-up-line text-[20px]"></i>
        <div class="back-to-top-wrap">
            <svg viewBox="-1 -1 102 102" class="w-[36px] h-[36px] fixed right-[16px] bottom-[16px]">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" class="fill-transparent stroke-[#64b496] stroke-[5px]" />
            </svg>
        </div>
    </a>

    @stack('scripts')

    <script type="text/javascript">
        {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
    </script>
</body>

</html>
