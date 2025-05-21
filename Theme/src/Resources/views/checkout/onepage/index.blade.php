<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
@endPush

<x-licious::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('licious::app.checkout.onepage.index.checkout')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.before') !!}

    <!-- Page Header -->
    <header class="py-4 bg-[#fff]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
            <div class="flex flex-wrap w-full justify-between">
                <div class="w-full px-[12px]">
                    <a
                        href="{{ route('shop.home.index') }}"
                        class="flex min-h-[30px]"
                        aria-label="@lang('shop::checkout.onepage.index.bagisto')"
                    >
                        <img
                            src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                            alt="{{ config('app.name') }}"
                            width="131"
                            height="29"
                        >
                    </a>
                </div>
                <div><div class="flex items-center"><span class="cursor-pointer text-base font-medium text-blue-700" role="button"> Sign In </span></div></div>
            </div>
        </div>
     </header>

    {!! view_render_event('bagisto.shop.checkout.onepage.header.after') !!}

    {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.before') !!}

        <!-- Breadcrumbs -->
        <x-licious::breadcrumbs name="checkout" />

    {!! view_render_event('bagisto.shop.checkout.onepage.breadcrumbs.after') !!}

    <!-- Page Content -->
    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">

        <!-- Checkout Vue Component -->
        <x-licious::checkout />
    </div>

</x-licious::layouts>
