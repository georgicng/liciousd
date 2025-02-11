@props(['type' => 'desktop'])

<!-- Mini Cart Vue Component -->
<v-filter-drawer>
    <a href="javascript:void(0)" class="shop_side_view h-[35px] w-[35px] flex justify-center items-center mr-[7px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] max-[360px]:mr-[7px]">
        <i class="ri-filter-line text-[20px]"></i>
    </a>
</v-filter-drawer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-filter-drawer-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        <x-licious::drawer isActive="false" class="cr-shop-leftside transition-all duration-[0.4s] ease fixed top-[0] overflow-x-auto z-[21]">
            <!-- Drawer Toggler -->
            <x-slot:toggle>
                <a href="javascript:void(0)" @click.prevent="open" class="shop_side_view h-[35px] w-[35px] flex justify-center items-center mr-[7px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] max-[360px]:mr-[7px]">
                    <i class="ri-filter-line text-[20px]"></i>
                </a>
            </x-slot>

            <x-slot:overlay>
                <div v-if="isOpen" class="filter-sidebar-overlay w-full h-screen fixed z-[20] top-[0] left-[0] bg-[#000000b3]"></div>
            </x-slot>

            <!-- Drawer Content -->
            <div class="cr-shop-leftside-inner w-[350px] h-[100vh] p-[0] m-[0] bg-[#fff] max-[575px]:w-[300px] max-[420px]:w-[250px]">
                <div class="cr-title p-[15px] flex flex-row justify-between items-center">
                    <h6 class="m-[0] text-[17px] font-bold text-[#2b2b2d] leading-[1.2]">@lang('licious::app.categories.filters.filter')</h6>
                    <a href="javascript:void(0)" class="close-shop-leftside text-[#fb5555]" @click="close">
                        <i class="ri-close-line text-[22px]"></i>
                    </a>
                </div>
                <div class="cr-shop-sideview p-[24px] bg-[#f7f7f8] border-[1px] border-solid border-[#e9e9e9] rounded-[0] sticky top-[30px]">
                    <x-licious::categories.filters @filter-applied="$emit('filter', $event)" @filter-clear="$emit('clear', $event)"/>
                </div>
                <p
                    class="text-xs font-medium cursor-pointer"
                    tabindex="0"
                    @click="clear()"
                >
                    @lang('licious::app.categories.filters.clear-all')
                </p>
            </div>
        </x-licious::drawer>

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component("v-filter-drawer", {
            template: '#v-filter-drawer-template',

            data() {
                return  {
                    cart: null,

                    isLoading:false,
                }
            },
        });
    </script>
@endpushOnce
