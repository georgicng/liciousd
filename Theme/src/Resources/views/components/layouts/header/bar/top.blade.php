{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
    <div class="flex flex-wrap w-full">
        <div class="w-full px-[12px]">
            <div class="top-header py-[20px] flex flex-row gap-[10px] justify-between border-b-[1px] border-solid border-[#e9e9e9] relative z-[4] max-[575px]:py-[15px] max-[575px]:block">
                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

                <a
                    href="{{ route('shop.home.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.bagisto')"
                    class="cr-logo max-[575px]:mb-[15px] max-[575px]:flex max-[575px]:justify-center"
                >
                    <img
                        src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                        alt="{{ config('app.name') }}"
                        class="logo block h-[35px] w-[115px] max-[575px]:w-[100px]"
                    >
                    <img src="{{ core()->getCurrentChannel()->dark_logo_url ?? bagisto_asset('images/dark-logo.svg') }}" alt="{{ config('app.name') }}" class="dark-logo hidden h-[35px] w-[115px] max-[575px]:w-[100px]">
                </a>

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

                <!-- Search Bar Container -->
                <x-licious::layouts.header.search />

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

                <div class="cr-right-bar flex max-[991px]:hidden">

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

                    <x-licious::layouts.header.menu.account-dropdown />

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

                    <!-- Compare -->
                    @if(core()->getConfigData('general.content.shop.compare_option'))
                        <a
                            href="{{ route('shop.compare.index') }}"
                            class="cr-right-bar-item pr-[30px] transition-all duration-[0.3s] ease-in-out flex items-center"
                            aria-label="@lang('shop::app.components.layouts.header.compare')"
                        >
                            <i class="ri-arrow-left-right-fill pr-[5px] text-[21px] leading-[17px]"></i>
                            <span class="transition-all duration-[0.3s] ease-in-out font-Poppins text-[15px] leading-[15px] font-medium text-[#000]" role="presentation">Compare</span>
                        </a>
                    @endif

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

                    <!-- Mini cart -->
                    <x-licious::drawer.cart type="desktop" />

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}
                </div>
            </div>
        </div>
    </div>
</div>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}
