<x-licious::drawer isActive="false" id="cr_mobile_menu" class="cr-side-cart cr-mobile-menu transition-all duration-[0.5s] ease h-full p-[15px] fixed top-[0] bg-[#fff] z-[22] overflow-auto w-[400px] left-[-400px] max-[575px]:w-[300px] max-[575px]:left-[-300px] max-[420px]:w-[250px] max-[420px]:left-[-250px]">
    <x-slot:toggle>
        <a href="#" @click.prevent="open" class="navbar-toggler py-[7px] px-[14px] hidden text-[16px] leading-[1] max-[991px]:flex max-[991px]:p-[0] max-[991px]:border-[0]">
            <i class="ri-menu-3-line max-[991px]:text-[20px]"></i>
        </a>
    </x-slot>

    <x-slot:overlay>
        <div class="cr-sidebar-overlay w-full h-screen hidden fixed top-[0] left-[0] bg-[#000000b3] z-[21]" v-show="isOpen"></div>
    </x-slot>

        <div class="cr-menu-title w-full mb-[10px] pb-[10px] flex flex-wrap justify-between border-b-[2px] border-solid border-[#e9e9e9]">
            <span class="menu-title text-[18px] font-semibold text-[#64b496]">My Menu</span>
            <button type="button" class="cr-close relative border-[0] text-[30px] leading-[1] text-[#999] bg-[#fff]" @click="close">×</button>
        </div>

        <div class="cr-menu-inner">
            <div class="cr-menu-content">
                <!-- Mobile category view -->
                <x-licious::layouts.header.menu.mobile />

                <!-- Localization & Currency Section -->
                <div class="absolute w-full flex bottom-0 left-0 bg-white shadow-lg p-4 gap-x-5 justify-between items-center mb-4">
                    <x-licious::dropdown position="top-left">
                        <!-- Dropdown Toggler -->
                        <x-slot:toggle>
                            <div class="w-full flex gap-2.5 justify-between items-center cursor-pointer" role="button">
                                <span>
                                    {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                </span>

                                <span
                                    class="icon-arrow-down text-2xl"
                                    role="presentation"
                                ></span>
                            </div>
                        </x-slot>

                        <!-- Dropdown Content -->
                        <x-slot:content class="!p-0">
                            <x-licious::currency-switcher />
                        </x-slot>
                    </x-licious::dropdown>

                    <x-licious::dropdown position="top-right">
                        <x-slot:toggle>
                            <!-- Dropdown Toggler -->
                            <div
                                class="w-full flex gap-2.5 justify-between items-center cursor-pointer"
                                role="button"
                            >
                                <img
                                    src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                            ? core()->getCurrentLocale()->logo_url
                                            : bagisto_asset('images/default-language.svg')
                                        }}"
                                    class="h-full"
                                    alt="Default locale"
                                    width="24"
                                    height="16"
                                />

                                <span>
                                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                                </span>

                                <span
                                    class="icon-arrow-down text-2xl"
                                    role="presentation"
                                ></span>
                            </div>
                        </x-slot>

                        <!-- Dropdown Content -->
                        <x-slot:content class="!p-0">
                            <v-locale-switcher></v-locale-switcher>
                        </x-slot>
                    </x-licious::dropdown>
                </div>
            </div>
        </div>
</x-licious::drawer>
