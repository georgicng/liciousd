<v-cart {{ $attributes }}>
    <!-- Cart Shimmer Effect -->
    <x-licious::shimmer.checkout.cart :count="3" />
</v-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-cart-template"
    >
        <!-- Cart Shimmer Effect -->
        <template v-if="isLoading">
            <x-licious::shimmer.checkout.cart :count="3" />
        </template>

        <section v-else class="section-blog-Classic">
            <div  class="flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
                <div class="flex flex-wrap w-full mb-[-24px]">
                    <!-- Cart Information -->
                    <template v-if="cart?.items?.length">
                        <div class="cr-cart-content min-[992px]:w-[75%] w-full px-[12px] mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                            <div
                                class="flex flex-wrap w-full max-[767px]:w-[700px]"

                            >
                                <form action="#" class="w-full">

                                    {!! view_render_event('bagisto.shop.checkout.cart.item.listing.before') !!}

                                    <!-- Cart Item Listing Container -->
                                    <div class="cr-table-content">
                                        <table class="w-full border-[1px] border-solid border-[#e9e9e9] rounded-[5px]">
                                            <thead>
                                                <tr class="border-[1px] border-solid border-[#e9e9e9]">
                                                    <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Product</th>
                                                    <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">price</th>
                                                    <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Quantity</th>
                                                    <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Total</th>
                                                    <th class="p-[15px] text-[#444] text-[15px] font-semibold text-left capitalize align-middle whitespace-nowrap leading-[1] tracking-[0] bg-[#e9e9e9]">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    class="border-b-[1px] border-solid border-[#e9e9e9] relative"
                                                    v-for="(item, index) in cart?.items"
                                                >
                                                    <!-- Cart Item Image -->
                                                    <td class="cr-cart-name w-[40%] py-[25px] px-[14px] text-[#444] text-[16px] text-left bg-[#f7f7f8]">
                                                        <div class="text-[#444] font-medium text-[14px] flex leading-[1.5] tracking-[0.6px] items-center">
                                                            <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
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
                                                            </a>
                                                            <div class="flex flex-col">
                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_name.before') !!}
                                                                <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
                                                                    <span
                                                                        v-text="item.name"
                                                                    >
                                                                    </span></a>
                                                                <!-- Cart Item Options Container -->
                                                                <div class="gap-y-2.5 relative z-20" v-if="item.options.length">

                                                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.before') !!}

                                                                    <button
                                                                        class="font-medium text-sm items-center inline-flex"
                                                                        type="button"
                                                                        @click="toggleItemOptions(item.id)">
                                                                        @lang('licious::app.checkout.cart.index.see-details')

                                                                        <span
                                                                            class="text-2xl"
                                                                            :class="{'ri-arrow-up-s-line': toggle[item.id], 'ri-arrow-down-s-line': !toggle[item.id]}"
                                                                        ></span>
                                                                    </button>

                                                                    <div
                                                                        class="bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-60 dark:bg-gray-700 dark:divide-gray-600"
                                                                        :class="{ 'hidden': !toggle[item.id], 'absolute z-50': toggle[item.id] }">
                                                                        <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200">
                                                                            <li v-for="option in item.options">
                                                                                <div class="flex p-2 rounded-sm">
                                                                                    <div class="ms-2 text-sm">
                                                                                        <div class="font-medium text-gray-900 dark:text-gray-300">
                                                                                            <div> @{{ option.attribute_name + ':' }}</div>
                                                                                            <p class="text-xs font-normal text-gray-500 dark:text-gray-300">@{{ option.option_label }}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <!-- Cart Item Options Container -->


                                                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.after') !!}
                                                                </div>
                                                                {!! view_render_event('bagisto.shop.checkout.cart.item_name.after') !!}
                                                            </div>
                                                        </div>

                                                    </td>

                                                    <td class="cr-cart-price py-[25px] px-[14px] text-[#555] text-[15px] font-medium text-left bg-[#f7f7f8]">
                                                        <span class="amount text-[#555] text-[15px] font-medium text-left" v-text="item.formatted_price"></span>
                                                    </td>

                                                    <td class="cr-cart-qty py-[25px] px-[14px] text-[#444] text-[16px] text-left bg-[#f7f7f8]">
                                                        {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.before') !!}

                                                        <x-licious::quantity-changer
                                                            name="quantity"
                                                            :type="'cart'"
                                                            ::value="item?.quantity"
                                                            @change="setItemQuantity(item.id, $event)"
                                                        />

                                                        {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.after') !!}
                                                    </td>
                                                    <td class="cr-cart-subtotal py-[25px] px-[14px] text-[#555] font-medium text-[15px] text-left bg-[#f7f7f8]">
                                                        {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.before') !!}
                                                        <span class="amount text-[#555] text-[15px] font-medium text-left" v-text="item.formatted_total"></span>
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
                                            <div class="cr-cart-update-bottom pt-[30px] flex justify-between items-center">
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
                                                    :title="trans('licious::app.checkout.cart.index.update-cart')"
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
                            <div class="cr-blog-sideview bg-[#fff] sticky top-[30px]">
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
                            alt="@lang('licious::app.checkout.cart.index.empty-product')"
                        />

                        <p
                            class="text-xl"
                            role="heading"
                        >
                            @lang('licious::app.checkout.cart.index.empty-product')
                        </p>
                    </div>
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
                    toggle: null
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
                            this.toggle = this.cart?.items.reduce((acc, item) => ({...acc, [item.id]: false }), {})

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
                    console.log(itemId, quantity)
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
                toggleItemOptions(id) {
                    this.toggle[id] = !this.toggle[id];
                }
            }
        });
    </script>
@endpushOnce
