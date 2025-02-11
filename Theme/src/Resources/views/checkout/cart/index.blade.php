<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.cart.index.cart')"/>

    <meta name="keywords" content="@lang('shop::app.checkout.cart.index.cart')"/>
@endPush

<x-licious::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('licious::app.checkout.cart.index.cart')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.cart.header.before') !!}

    <!-- Page Header -->
    <div class="flex flex-wrap">
        <div class="w-full flex justify-between px-[60px] border border-t-0 border-b border-l-0 border-r-0 py-4 max-lg:px-8 max-sm:px-4">
            <div class="flex items-center gap-x-14 max-[1180px]:gap-x-9">
                {!! view_render_event('bagisto.shop.checkout.cart.logo.before') !!}

                <a
                    href="{{ route('shop.home.index') }}"
                    class="flex min-h-[30px]"
                    aria-label="@lang('shop::app.checkout.cart.index.bagisto')"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        width="131"
                        height="29"
                    >
                </a>

                {!! view_render_event('bagisto.shop.checkout.cart.logo.after') !!}
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.checkout.cart.header.after') !!}

    {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.before') !!}

        <!-- Breadcrumbs -->
        <x-licious::breadcrumbs name="cart" />

    {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.after') !!}

    <section class="section-cart pt-[100px] max-[1199px]:pt-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
            <div class="flex flex-wrap w-full hidden">
                <div class="w-full px-[12px]">
                    <div class="mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                        <div class="cr-banner mb-[15px] text-center">
                            <h2 class="font-Manrope text-[32px] font-bold leading-[1.2] text-[#2b2b2d] max-[1199px]:text-[28px] max-[991px]:text-[25px] max-[767px]:text-[22px]">Cart</h2>
                        </div>
                        <div class="cr-banner-sub-title w-full">
                            <p class="max-w-[600px] m-auto font-Poppins text-[14px] text-[#212529] leading-[22px] text-center max-[1199px]:w-[80%] max-[991px]:w-full">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                                ut labore lacus vel facilisis. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap w-full">
                <div class="w-full px-[12px]">
                    <div class="cr-cart-content max-[767px]:overflow-x-scroll" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                        <x-licious::cart ref="vCart" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.before') !!}

    <!-- Cross-sell Product Carousal -->
    <x-licious::products.carousel
        :title="trans('shop::app.checkout.cart.index.cross-sell.title')"
        :src="route('shop.api.checkout.cart.cross-sell.index')"
    >
    </x-licious::products.carousel>

    {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.after') !!}


</x-licious::layouts>
