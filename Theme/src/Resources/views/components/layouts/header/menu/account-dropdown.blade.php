<!-- user profile -->
<x-licious::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
    <x-slot:toggle>
        <a class="nav-link dropdown-toggle cr-right-bar-item transition-all duration-[0.3s] ease-in-out flex items-center relative text-[14px] font-medium text-[#000] z-[1] relative py-[11px] pr-[30px] pl-[8px] max-[1199px]:py-[8px]" href="javascript:void(0)" role="button"
            aria-label="@lang('shop::app.components.layouts.header.profile')"
            tabindex="0">
            <i class="ri-user-3-line pr-[5px] text-[21px] leading-[17px]"></i>
            <span class="transition-all duration-[0.3s] ease-in-out font-Poppins text-[15px] leading-[15px] text-[15px] font-medium text-[#000] tracking-[0.03rem]">Account</span>
        </a>
    </x-slot>

    <!-- Guest Dropdown -->
    @guest('customer')
        <x-slot:content>
            <div class="grid gap-2.5">
                <p class="text-xl font-dmserif">
                    @lang('licious::app.components.layouts.header.welcome-guest')
                </p>

                <p class="text-sm">
                    @lang('licious::app.components.layouts.header.dropdown-text')
                </p>
            </div>

            <p class="w-full mt-3 py-2px border border-[#E9E9E9]"></p>

            <div class="flex gap-4 mt-6">
                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                <a
                    href="{{ route('shop.customer.session.create') }}"
                    class="primary-button block w-max px-7 mx-auto m-0 ltr:ml-0 rtl:mr-0 rounded-2xl text-base text-center"
                >
                    @lang('licious::app.components.layouts.header.sign-in')
                </a>

                <a
                    href="{{ route('shop.customers.register.index') }}"
                    class="secondary-button block w-max m-0 ltr:ml-0 rtl:mr-0 mx-auto px-7 border-2 rounded-2xl text-base text-center"
                >
                    @lang('licious::app.components.layouts.header.sign-up')
                </a>

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up__button.after') !!}
            </div>
        </x-slot>
    @endguest

    <!-- Customers Dropdown -->
    @auth('customer')
        <x-slot:content class="!p-0">
            <div class="grid gap-2.5 p-5 pb-0">
                <p class="text-xl font-dmserif">
                    @lang('licious::app.components.layouts.header.welcome')’
                    {{ auth()->guard('customer')->user()->first_name }}
                </p>

                <p class="text-sm">
                    @lang('licious::app.components.layouts.header.dropdown-text')
                </p>
            </div>

            <p class="w-full mt-3 py-2px border border-[#E9E9E9]"></p>

            <div class="grid gap-1 mt-2.5 pb-2.5">
                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                <a
                    class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                    href="{{ route('shop.customers.account.profile.index') }}"
                >
                    @lang('licious::app.components.layouts.header.profile')
                </a>

                <a
                    class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                    href="{{ route('shop.customers.account.orders.index') }}"
                >
                    @lang('licious::app.components.layouts.header.orders')
                </a>

                @if (core()->getConfigData('general.content.shop.wishlist_option'))
                    <a
                        class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                        href="{{ route('shop.customers.account.wishlist.index') }}"
                    >
                        @lang('licious::app.components.layouts.header.wishlist')
                    </a>
                @endif

                <!--Customers logout-->
                @auth('customer')
                    <x-licious::form
                        method="DELETE"
                        action="{{ route('shop.customer.session.destroy') }}"
                        id="customerLogout"
                    />

                    <a
                        class="px-5 py-2 text-base hover:bg-gray-100 cursor-pointer"
                        href="{{ route('shop.customer.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                    >
                        @lang('licious::app.components.layouts.header.logout')
                    </a>
                @endauth

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
            </div>
        </x-slot>
    @endauth
</x-licious::dropdown>
