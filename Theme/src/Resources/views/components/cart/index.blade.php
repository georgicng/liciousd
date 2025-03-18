<v-cart {{ $attributes }}>
    <!-- Cart Shimmer Effect -->
    <x-licious::shimmer.checkout.cart :count="3" />
</v-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-template"
    >
        <section class="section-blog-Classic">
            <div class="flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
                <div class="flex flex-wrap w-full mb-[-24px]">
                
                    <!-- Cart Shimmer Effect -->
                    <template v-if="isLoading">
                        <x-licious::shimmer.checkout.cart :count="3" />
                    </template>

                    <!-- Cart Information -->
                    <template v-else>
                        <template v-if="cart?.items?.length">
                            <div class="cr-cart-content min-[992px]:w-[75%] w-full px-[12px] mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                                <div
                                    class="flex flex-wrap w-full max-[767px]:w-[700px]"
                                    
                                >
                                    <form action="#" class="w-full">

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.listing.before') !!}

                                        <!-- Cart Item Listing Container -->
                                        <div class="cr-table-content">
                                            <table class="w-full border-[1px] border-solid border-[#e9e9e9] rounded-[5px] overflow-hidden">
                                                <thead>
                                                    <tr class="border-[1px] border-solid border-[#e9e9e9]">
                                                        <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Product</th>
                                                        <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">price</th>
                                                        <th class="p-[15px] text-[#444] text-[15px] font-semibold text-center capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Quantity</th>
                                                        <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Total</th>
                                                        <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b-[1px] border-solid border-[#e9e9e9]"
                                                        v-for="item in cart?.items"
                                                    >
                                                        <!-- Cart Item Image -->
                                                        <td class="cr-cart-name w-[40%] py-[25px] px-[14px] text-[#444] text-[16px] text-left bg-[#f7f7f8]">
                                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`" class="text-[#444] font-medium text-[14px] flex leading-[1.5] tracking-[0.6px] items-center">
                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_image.before') !!}
                                                                <x-licious::media.images.lazy
                                                                    class="cr-cart-img mr-[20px] w-[60px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]"
                                                                    ::src="item.base_image.small_image_url"
                                                                    ::alt="item.name"
                                                                    width="110"
                                                                    height="110"
                                                                    ::key="item.id"
                                                                    ::index="item.id"
                                                                />
                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_image.after') !!}

                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_name.before') !!}
                                                                <span
                                                                    v-text="item.name"
                                                                >
                                                                </span>
                                                                <!-- Cart Item Options Container -->
                                                                <div class="grid place-content-start gap-y-2.5">

                                                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.before') !!}

                                                                    <!-- Cart Item Options Container -->
                                                                    <div
                                                                        class="grid gap-x-2.5 gap-y-1.5 select-none"
                                                                        v-if="item.options.length"
                                                                    >
                                                                        <!-- Details Toggler -->
                                                                        <div class="">
                                                                            <p
                                                                                class="flex gap-x-1.5 text-base items-center cursor-pointer whitespace-nowrap"
                                                                                @click="item.option_show = ! item.option_show"
                                                                            >
                                                                                @lang('licious::app.checkout.cart.index.see-details')

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
                                                                    </div>

                                                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.after') !!}
                                                                </div>
                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_name.after') !!}
                                                            </a>

                                                        </td>

                                                        <td class="cr-cart-price py-[25px] px-[14px] text-[#555] text-[15px] font-medium text-left bg-[#f7f7f8]">
                                                            <span class="amount text-[#555] text-[15px] font-medium text-left" v-text="item.formatted_total"></span>
                                                        </td>

                                                        <td class="cr-cart-qty py-[25px] px-[14px] text-[#444] text-[16px] text-left bg-[#f7f7f8]">
                                                            {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.before') !!}

                                                            <x-licious::quantity-changer
                                                                class="flex gap-x-2.5 border rounded-[54px] border-navyBlue py-1.5 px-3.5 items-center max-w-max"
                                                                name="quantity"
                                                                :type="'cart'"
                                                                ::value="item?.quantity"
                                                                @change="setItemQuantity(item.id, $event)"
                                                            />

                                                            {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.after') !!}
                                                        </td>
                                                        <td class="cr-cart-subtotal py-[25px] px-[14px] text-[#555] font-medium text-[15px] text-left bg-[#f7f7f8]">
                                                            {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.before') !!}

                                                            <div class="sm:hidden">
                                                                <p
                                                                    class="text-lg font-semibold"
                                                                    v-text="item.formatted_total"
                                                                >
                                                                </p>

                                                                <span
                                                                    class="text-base text-[#0A49A7] cursor-pointer"

                                                                >
                                                                    @lang('licious::app.checkout.cart.index.remove')
                                                                </span>
                                                            </div>

                                                            {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.after') !!}
                                                        </td>
                                                        <td class="cr-cart-remove py-[25px] px-[14px] w-[90px] text-[#555] font-medium text-[15px] text-right bg-[#f7f7f8]">
                                                            <a href="javascript:void(0)"
                                                                class="transition-all duration-[0.3s] ease-in-out my-[0] mx-auto text-[#555] hover:text-[#fb5555]"
                                                                role="button"
                                                                tabindex="0"
                                                                aria-label="@lang('licious::app.checkout.cart.index.remove')"
                                                                @click="removeItem(item.id)">
                                                                <i class="ri-delete-bin-line text-[22px]"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item.listing.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.cart.controls.before') !!}

                                        <!-- Cart Item Actions -->
                                        <div class="flex flex-wrap w-full">
                                            <div class="w-full">
                                                <div class="cr-cart-update-bottom pt-[30px] flex justify-between">
                                                    {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.before') !!}
                                                    <a
                                                        class="cr-links text-[#444] inline-block underline-[1px] text-[15px] leading-[20px] font-medium tracking-[0.8px]"
                                                        href="{{ route('shop.home.index') }}"
                                                    >
                                                        @lang('licious::app.checkout.cart.index.continue-shopping')
                                                    </a>

                                                    {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.after') !!}

                                                    {!! view_render_event('bagisto.shop.checkout.cart.update_cart.before') !!}

                                                    <x-licious::button
                                                        class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
                                                        :title="trans('shop::app.checkout.cart.index.update-cart')"
                                                        ::loading="isStoring"
                                                        ::disabled="isStoring"
                                                        @click="update()"
                                                    />

                                                    {!! view_render_event('bagisto.shop.checkout.cart.update_cart.after') !!}
                                                </div>
                                            </div>
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.controls.after') !!}
                                    </form>  
                                </div>
                            
                            </div>
                            <div class="min-[992px]:w-[25%] w-full px-[12px] mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                                <div class="cr-blog-sideview p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] sticky top-[30px]">
                                    {!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

                                    <!-- Cart Summary -->
                                    <x-licious::cart.summary />

                                    {!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}
                                </div>
                            </div>
                        </template>
                        <!-- Empty Cart Section -->
                        <div
                            class="grid items-center justify-items-center w-full m-auto h-[476px] place-content-center text-center"
                            v-else
                        >
                            <img
                                src="{{ bagisto_asset('images/thank-you.png') }}"
                                alt="@lang('shop::app.checkout.cart.index.empty-product')"
                            />

                            <p
                                class="text-xl"
                                role="heading"
                            >
                                @lang('licious::app.checkout.cart.index.empty-product')
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </section>
    </script>

    <script type="module">
        app.component("v-cart", {
            template: '#v-cart-template',

            data() {
                return  {
                    cart: [],

                    allSelected: false,

                    applied: {
                        quantity: {},
                    },

                    isLoading: true,

                    isStoring: false,
                }
            },

            mounted() {
                this.getCart();
            },

            computed: {
                selectedItemsCount() {
                    return this.cart.items.filter(item => item.selected).length;
                },
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;

                            this.isLoading = false;

                            if (response.data.message) {
                                this.$emitter.emit('add-flash', { type: 'info', message: response.data.message });
                            }
                        })
                        .catch(error => {});
                },

                selectAll() {
                    for (let item of this.cart.items) {
                        item.selected = this.allSelected;
                    }
                },

                updateAllSelected() {
                    this.allSelected = this.cart.items.every(item => item.selected);
                },

                update() {
                    this.isStoring = true;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                        .then(response => {
                            this.cart = response.data.data;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.isStoring = false;

                        })
                        .catch(error => {
                            this.isStoring = false;
                        });
                },

                setItemQuantity(itemId, quantity) {
                    this.applied.quantity[itemId] = quantity;
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
                                .catch(error => {});
                        }
                    });
                },

                removeSelectedItems() {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy_selected') }}', {
                                    '_method': 'DELETE',
                                    'ids': selectedItemsIds,
                                })
                                .then(response => {
                                    this.cart = response.data.data;

                                    this.$emitter.emit('update-mini-cart', response.data.data );

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                })
                                .catch(error => {});
                        }
                    });
                },

                moveToWishlistSelectedItems() {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                            const selectedItemsQty = this.cart.items.filter(item => item.selected).map(item => this.applied.quantity[item.id] ?? item.quantity);

                            this.$axios.post('{{ route('shop.api.checkout.cart.move_to_wishlist') }}', {
                                    'ids': selectedItemsIds,
                                    'qty': selectedItemsQty
                                })
                                .then(response => {
                                    this.cart = response.data.data;

                                    this.$emitter.emit('update-mini-cart', response.data.data );

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                })
                                .catch(error => {});
                        }
                    });
                },
            }
        });
    </script>
@endpushOnce
