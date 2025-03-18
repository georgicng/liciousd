@props(['type' => 'desktop'])

<!-- Mini Cart Vue Component -->
<v-mini-cart>
    @if ($type === "desktop")
        <a class="relative cr-right-bar-item Shopping-toggle transition-all duration-[0.3s] ease-in-out flex items-center" role="button"
            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
            tabindex="0">
            <i class="ri-shopping-cart-line pr-[5px] text-[21px] leading-[17px]"></i>
            <span class="transition-all duration-[0.3s] ease-in-out font-Poppins text-[15px] leading-[15px] font-medium text-[#000]">Cart</span>
        </a>
    @else
        <a class="relative cr-right-bar-item Shopping-toggle transition-all duration-[0.3s] ease-in-out mr-[16px] max-[991px]:m-[0]">
            <i class="ri-shopping-cart-line text-[20px]"></i>
        </a>
    @endif
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        <x-licious::drawer isActive="false" class="cr-cart-view right-[0]">
            <!-- Drawer Toggler -->
            <x-slot:toggle>
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                @if ($type === "desktop")
                    <a href="#" @click.prevent="open" class="relative cr-right-bar-item Shopping-toggle transition-all duration-[0.3s] ease-in-out flex items-center" role="button"
                        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                        tabindex="0">
                        <i class="ri-shopping-cart-line pr-[5px] text-[21px] leading-[17px]"></i>
                        <span class="transition-all duration-[0.3s] ease-in-out font-Poppins text-[15px] leading-[15px] font-medium text-[#000]">Cart</span>

                        <span
                            class="absolute px-2 -top-4 ltr:left-5 rtl:right-5 py-1.5 bg-[#060C3B] rounded-[44px] text-white text-xs font-semibold leading-[9px]"
                            v-if="cart?.items_qty"
                        >
                            @{{ cart.items_qty }}
                        </span>
                    </a>
                @else
                    <a href="#" @click.prevent="open" class="relative cr-right-bar-item Shopping-toggle transition-all duration-[0.3s] ease-in-out mr-[16px] max-[991px]:m-[0]">
                        <i class="ri-shopping-cart-line text-[20px]"></i>
                        <span
                            class="absolute px-2 -top-4 ltr:left-5 rtl:right-5 py-1.5 bg-[#060C3B] rounded-[44px] text-white text-xs font-semibold leading-[9px]"
                            v-if="cart?.items_qty"
                        >
                            @{{ cart.items_qty }}
                        </span>
                    </a>
                @endif

                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
            </x-slot>

            <x-slot:overlay>
                <div v-if="isOpen" class="cr-cart-overlay w-full h-screen fixed z-[20] top-[0] left-[0] bg-[#000000b3]"></div>
            </x-slot>

            <!-- Drawer Content -->

            <div class="cr-cart-top text-[#000]">
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}
                <div class="cr-cart-title mb-[15px] py-[15px] flex flex-row justify-between items-center border-b-[1px] border-solid border-[#e9e9e9]">
                    <h6 class="m-[0] text-[17px] font-bold text-[#2b2b2d] leading-[1.2]">@lang('shop::app.checkout.cart.mini-cart.shopping-cart')</h6>
                    <button type="button" class="close-cart text-[#fb5555] text-[20px] font-extrabold bg-none border-[0]" @click="close">×</button>
                </div>
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}

                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}
                <!-- Cart Item Listing -->
                <ul
                    class="crcart-pro-items"
                    v-if="cart?.items?.length"
                >
                    <li
                        class="mb-[20px] pb-[20px] flex overflow-hidden border-b-[1px] border-solid border-[#e9e9e9]"
                        v-for="item in cart?.items"
                       :key="item?.id">
                        <!-- Cart Item Image -->
                        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.before') !!}
                        <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`" class="crside_pro_img m-auto grow-[1] basis-[20%]">
                            <img :src="item.base_image.small_image_url" alt="product-1" class="max-w-full rounded-[5px]">
                        </a>
                        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.after') !!}

                        <!-- Cart Item Information -->
                        <div class="cr-pro-content pl-[15px] relative grow-[1] basis-[70%] overflow-hidden">
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.before') !!}
                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`" v-text="item.name" class="cart_pro_title w-full pr-[30px] whitespace-normal overflow-hidden text-ellipsis block text-[15px] leading-[18px] font-normal"></a>
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.before') !!}
                            <span class="cart-price mt-[5px] text-[14px] block"><span v-text="item.formatted_price" class="text-[#777] font-bold text-[16px]"></span></span>
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.after') !!}

                            <!-- Cart Item Options Container -->
                            <div
                                class="grid gap-x-2.5 gap-y-1.5 select-none"
                                v-if="item.options.length"
                            >

                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.before') !!}

                                <!-- Details Toggler -->
                                <div class="">
                                    <p
                                        class="flex gap-x-[15px] items-center text-base cursor-pointer"
                                        @click="item.option_show = ! item.option_show"
                                    >
                                        @lang('licious::app.checkout.cart.mini-cart.see-details')

                                        <span
                                            class="text-2xl"
                                            :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                        ></span>
                                    </p>
                                </div>

                                <!-- Option Details -->
                                <div class="grid gap-2" v-show="item.option_show">
                                    <div class="" v-for="option in item.options">
                                        <p class="text-sm font-medium">
                                            @{{ option.attribute_name + ':' }}
                                        </p>

                                        <p class="text-sm">
                                            @{{ option.option_label }}
                                        </p>
                                    </div>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.after') !!}
                            </div>

                            <div class="cr-cart-qty mt-[5px]">
                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.before') !!}

                                <!-- Cart Item Quantity Changer -->
                                <x-licious::quantity-changer
                                    class="gap-x-2.5 max-w-[150px] max-h-9 py-1.5 px-3.5 rounded-[54px]"
                                    name="quantity"
                                    :type="'cart'"
                                    ::value="item?.quantity"
                                    @change="updateItem($event, item)"
                                />

                                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.after') !!}
                            </div>
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.before') !!}
                            <a href="javascript:void(0)" @click="removeItem(item.id)" class="remove py-[0] px-[9px] absolute top-[0] right-[0] text-[17px] leading-[15px] bg-[#fff] text-[#fb5555]">×
                                <span class="sr-only">@lang('shop::app.checkout.cart.mini-cart.remove')</span>
                            </a>
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.after') !!}
                        </div>
                    </li>
                </ul>

                <!-- Empty Cart Section -->
                <div
                    class="pb-8"
                    v-else
                >
                    <div class="grid gap-y-5 b-0 place-items-center">
                        <img src="{{ bagisto_asset('images/thank-you.png') }}">

                        <p class="text-xl">
                            @lang('licious::app.checkout.cart.mini-cart.empty-cart')
                        </p>
                    </div>
                </div>
            </div>
            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}


            <div v-if="cart?.items?.length" class="cr-cart-bottom relative top-[-20px]">
                <div class="cart-sub-total mt-[20px] mb-[10px] pt-[0] pb-[8px] flex flex-wrap justify-between border-t-[1px] border-solid border-[#e9e9e9]">
                {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.before') !!}
                    <table v-if="! isLoading" class="table cart-table mt-[10px] w-full">
                        <tbody>
                            <tr>
                                <td class="text-left text-[16px] text-[#000] font-normal py-[7px]">@lang('shop::app.checkout.cart.mini-cart.subtotal') :</td>
                                <td v-text="cart.formatted_grand_total" class="text-right text-[15px] text-[#000] font-bold py-[7px]"></td>
                            </tr>
                            <!--tr>
                                <td class="text-left text-[16px] text-[#000] font-normal py-[7px]">VAT (20%) :</td>
                                <td class="text-right text-[15px] text-[#000] font-bold py-[7px]">$60.00</td>
                            </tr>
                            <tr>
                                <td class="text-left text-[16px] text-[#000] font-normal py-[7px]">Total :</td>
                                <td class="text-right text-[15px] text-[#000] font-bold py-[7px]">$360.00</td>
                            </tr-->
                        </tbody>
                    </table>
                {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.after') !!}
                    <div
                        v-else
                        class="flex justify-center items-center"
                    >
                        <!-- Spinner -->
                        <svg
                            class="absolute animate-spin  h-8 w-8  text-[5px] font-semibold text-blue"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            aria-hidden="true"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>

                        <span class="opacity-0 realative text-3xl font-semibold" v-text="cart.formatted_grand_total"></span>
                    </div>
                </div>
                <div class="cart_btn flex justify-between">
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.before') !!}
                    <a href="{{ route('shop.checkout.cart.index') }}"
                        class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]">@lang('shop::app.checkout.cart.mini-cart.view-cart')</a>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.after') !!}
                    <a href="{{ route('shop.checkout.onepage.index') }}" class="cr-btn-secondary h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#fff] text-[#64b496] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:text-[#fff] hover:bg-[#64b496] hover:border-[#64b496]">@lang('shop::app.checkout.cart.mini-cart.continue-to-checkout')</a>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.action.after') !!}
                </div>
            </div>
        </x-licious::drawer>

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    cart: null,

                    isLoading:false,
                }
            },

            mounted() {
                this.getCart();

                /**
                 * To Do: Implement this.
                 *
                 * Action.
                 */
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                updateItem(quantity, item) {
                    this.isLoading = true;

                    let qty = {};

                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                        .then(response => {
                            if (response.data.message) {
                                this.cart = response.data.data;
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isLoading = false;
                        }).catch(error => this.isLoading = false);
                },

                removeItem(itemId) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                '_method': 'DELETE',
                                'cart_item_id': itemId,
                            })
                            .then(response => {
                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                    this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                            });
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
