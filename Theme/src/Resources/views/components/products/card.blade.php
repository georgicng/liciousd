<v-product-card
    {{ $attributes }}
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <div
            class="min-[992px]:w-[25%] w-[50%] max-[480px]:w-full px-[12px] cr-product-box mb-[24px]"
        >
            <div class="cr-product-card h-full p-[12px] border-[1px] border-solid border-[#e9e9e9] bg-[#fff] rounded-[5px] overflow-hidden flex-col max-[480px]:w-full">
                <div class="cr-product-image rounded-[5px] flex items-center justify-center relative">

                    {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                    <div class="cr-image-inner zoom-image-hover w-full h-full flex items-center justify-center relative overflow-hidden max-[991px]:pointer-events-none">
                        <x-licious::media.images.lazy
                            class="relative after:content-[' '] after:block after:pb-[calc(100%+9px)] bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
                            ::src="product.base_image.medium_image_url"
                            ::key="product.id"
                            ::index="product.id"
                            width="291"
                            height="300"
                            ::alt="product.name"
                        />
                    </div>

                    {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                    <div class="cr-side-view transition-all duration-[0.4s] ease-in-out absolute z-[20] top-[15px] right-[-40px] grid opacity-0 max-[991px]:right-[12px]">
                        <!--p
                            class="inline-block absolute top-5 ltr:left-5 rtl:right-5 px-2.5  bg-[#E51A1A] rounded-[44px] text-white text-sm"
                            v-if="product.on_sale"
                        >
                            @lang('licious::app.components.products.card.sale')
                        </p>

                        <p
                            class="inline-block absolute top-5 ltr:left-5 rtl:right-5 px-2.5 bg-navyBlue rounded-[44px] text-white text-sm"
                            v-else-if="product.is_new"
                        >
                            @lang('licious::app.components.products.card.new')
                        </p-->
                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                            @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                <a
                                    href="javascript:void(0)"
                                    class="wishlist h-[35px] w-[35px] flex items-center justify-center m-0 p-0 bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[100%]"
                                    role="button"
                                    aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                                    tabindex="0"
                                    @click="addToWishlist()">
                                    <i :class="product.is_wishlist ? 'ri-heart-line' : 'icon-heart'" class="text-[18px] leading-[10px]"></i>
                                </a>
                            @endif

                        {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                            @if (core()->getConfigData('general.content.shop.compare_option'))
                                <a
                                    class="model-oraganic-product h-[35px] w-[35px] flex items-center justify-center m-0 p-0 bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[100%] mt-[5px] cursor-pointer cr-modal-toggle"
                                    role="button"
                                    aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                                    tabindex="0"
                                    @click="addToCompare(product.id)">
                                    <i class="ri-eye-line text-[18px] leading-[10px]"></i>
                                </a>
                            @endif

                        {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}

                    </div>
                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}
                        <a
                            class="cr-shopping-bag h-[35px] w-[35px] absolute bottom-[-16px] flex items-center justify-center m-0 p-0 bg-[#f7f7f8] border-[1px] border-solid border-[#e9e9e9] rounded-[100%]"
                            href="javascript:void(0)"
                            :disabled="! product.is_saleable || isAddingToCart"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-cart')"
                            @click="addToCart()">
                            <i class="ri-shopping-bag-line text-[#64b496]"></i>
                        </a>
                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}
                </div>

                <div class="cr-product-details pt-[24px] text-center overflow-hidden max-[1199px]:pt-[20px]">
                    <div class="cr-brand">
                        <a href="{{ route('shop.product_or_category.index', '') }}" class="transition-all duration-[0.3s] ease-in-out mb-[5px] text-[13px] text-[#777] flex justify-center">Snacks</a>
                        <div class="cr-star mb-[12px] flex justify-center items-center">
                            <i class="ri-star-fill mx-[1px] text-[15px] text-[#f5885f]"></i>
                            <i class="ri-star-fill mx-[1px] text-[15px] text-[#f5885f]"></i>
                            <i class="ri-star-fill mx-[1px] text-[15px] text-[#f5885f]"></i>
                            <i class="ri-star-fill mx-[1px] text-[15px] text-[#f5885f]"></i>
                            <i class="ri-star-fill mx-[1px] text-[15px] text-[#f5885f]"></i>
                            <p class="mb-[0] font-Poppins ml-[5px] text-[#999] text-[11px] leading-[10px]">(5.0)</p>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}
                    <a
                        :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`" class="title transition-all duration-[0.3s] ease-in-out mb-[12px] font-Poppins text-[15px] font-medium leading-[24px] text-[#2b2b2d] hover:text-[#64b496] flex justify-center"
                        v-text="product.name"></a>
                    <p class="text text-[14px] font-Poppins text-[#7a7a7a] leading-[1.75] text-left mb-[10px] hidden"></p>

                    {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                    {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                    <p class="cr-price font-Poppins text-[16px] text-[#7a7a7a] leading-[1.75] max-[1199px]:text-[14px]" v-html="product.price_html"></p>

                    {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    isAddingToCart: false,
                }
            },

            methods: {
                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                },

                addToCompare(productId) {
                    /**
                     * This will handle for customers.
                     */
                    if (this.isCustomer) {
                        this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                'product_id': productId
                            })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if ([400, 422].includes(error.response.status)) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                    return;
                                }

                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                            });

                        return;
                    }

                    /**
                     * This will handle for guests.
                     */
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {

                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.data.redirect_uri) {
                                window.location.href = response.data.data.redirect_uri;
                            }

                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data );

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.isAddingToCart = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                        });
                },
            },
        });
    </script>
@endpushOnce
