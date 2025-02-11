@props(['product', 'customAttributeValues'])

<v-product {{ $attributes}}>
    <x-licious::shimmer.products.view />
</v-product>


@pushOnce('scripts')
    <script type="text/x-template" id="v-product-template">
        <div class="flex flex-wrap w-full mb-[-24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
            <div class="min-[1400px]:w-[33.33%] min-[1200px]:w-[41.66%] min-[768px]:w-[50%] w-full px-[12px] mb-[24px]">
                <!-- Gallery Blade Inclusion -->
                <x-licious::products.gallery :product="$product" />
            </div>
            <div class="min-[1400px]:w-[66.66%] min-[1200px]:w-[58.33%] min-[768px]:w-[50%] w-full px-[12px] mb-[24px]">
                <div class="cr-size-and-weight-contain border-b-[1px] border-solid border-[#e9e9e9] pb-[20px] max-[767px]:mt-[24px]">
                    {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}
                        <h2 class="heading mb-[15px] block text-[#2b2b2d] text-[22px] leading-[1.5] font-medium max-[1399px]:text-[26px] max-[991px]:text-[20px]">{{ $product->name }}</h2>
                    {!! view_render_event('bagisto.shop.products.name.after', ['product' => $product]) !!}

                    {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}
                        <p class="mb-[0] text-[14px] font-Poppins text-[#7a7a7a] leading-[1.75] ">{!! $product->short_description !!}</p>
                    {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}
                </div>
                <div class="cr-size-and-weight pt-[20px]">
                    <!-- Rating -->
                    {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}
                    <div class="cr-review-star flex">
                        <x-licious::products.star-rating :value="$avgRatings" :is-editable=false />
                        <p class="mb-[0] text-[15px] font-Poppins text-[#7a7a7a] leading-[1.75] max-[380px]:hidden">({{ $product->approvedReviews->count() }} @lang('reviews'))</p>
                    </div>
                    {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}
                    <!--div class="list">
                        <ul class="mt-[15px] p-[0] mb-[1rem]">
                            @foreach ($customAttributeValues as $customAttributeValue)
                                <li class="py-[5px] text-[#777] flex">
                                    @if (! empty($customAttributeValue['value']))
                                        <label class="min-w-[100px] mr-[10px] text-[#2b2b2d] font-semibold flex justify-between">
                                            {!! $customAttributeValue['label'] !!} <span>:</span>
                                        </label>

                                        @if ($customAttributeValue['type'] == 'file')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <span class="icon-download text-2xl"></span>
                                            </a>
                                        @elseif ($customAttributeValue['type'] == 'image')
                                            <a
                                                href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                download="{{ $customAttributeValue['label'] }}"
                                            >
                                                <img
                                                    class="h-5 w-5 min-h-5 min-w-5"
                                                    src="{{ Storage::url($customAttributeValue['value']) }}"
                                                />
                                            </a>
                                        @else
                                            {!! $customAttributeValue['value'] !!}
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div-->

                    <!-- Pricing -->
                    {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}
                    <div class="cr-product-price pt-[20px]">
                        {!! $product->getTypeInstance()->getPriceHtml() !!}

                        @if (
                            (bool) core()->getConfigData('taxes.catalogue.pricing.tax_inclusive')
                            && $product->getTypeInstance()->getTaxCategory()
                        )
                            <span class="text-lg text-[#6E6E6E]">@lang('licious::app.products.view.tax-inclusive')</span>
                        @endif

                        @if (count($product->getTypeInstance()->getCustomerGroupPricingOffers()))
                            <div class="grid gap-1.5 mt-2.5">
                                @foreach ($product->getTypeInstance()->getCustomerGroupPricingOffers() as $offer)
                                    <p class="text-[#6E6E6E] [&>*]:text-black">
                                        {!! $offer !!}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                    <x-licious::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <form
                            ref="formData"
                            @submit="handleSubmit($event, addToCart)"
                        >
                            <input
                                type="hidden"
                                name="product_id"
                                value="{{ $product->id }}"
                            >

                            <input
                                type="hidden"
                                name="is_buy_now"
                                v-model="is_buy_now"
                            >

                            <input
                                type="hidden"
                                name="quantity"
                                :value="qty"
                            >

                            @if (Webkul\Product\Helpers\ProductType::hasVariants($product->type))
                                <x-licious::products.types.configurable :product="$product" />
                            @endif

                            @if ($product->type == 'grouped')
                                <x-licious::products.types.grouped :product="$product" />
                            @endif

                            @if ($product->type == 'bundle')
                                <x-licious::products.types.bundle :product="$product" />
                            @endif

                            @if ($product->type == 'downloadable')
                                <x-licious::products.types.downloadable :product="$product" />
                            @endif

                            <!-- Product Actions and Qunatity Box -->
                            <div class="cr-add-card flex pt-[20px]">

                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                @if ($product->getTypeInstance()->showQuantityBox())
                                    <x-licious::quantity-changer
                                        type="product"
                                        name="quantity"
                                        value="1"
                                        class="gap-x-4 py-4 px-7 rounded-xl"
                                    />
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                <!-- Add To Cart Button -->
                                {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}
                                <div class="cr-add-button ml-[15px] max-[380px]:hidden">
                                    <x-licious::button
                                        type="submit"
                                        class="cr-button cr-shopping-bag h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000] max-[1199px]:py-[8px] max-[1199px]:px-[15px]"
                                        button-type="secondary-button"
                                        :loading="false"
                                        :title="trans('shop::app.products.view.add-to-cart')"
                                        :disabled="! $product->isSaleable(1)"
                                        ::loading="isStoring.addToCart"
                                    />
                                </div>

                                {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}

                                <div class="cr-card-icon flex ml-[15px]">
                                    @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                        <a
                                            href="javascript:void(0)"
                                            class="wishlist m-[0] p-[0] bg-transparent"
                                            role="button"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                            tabindex="0"
                                            :class="isWishlist ? 'icon-heart-fill' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                            <i class="ri-heart-line transition-all duration-[0.3s] ease-in-out h-[40px] w-[40px] mr-[10px] flex items-center justify-center text-[22px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] hover:bg-[#64b496] hover:text-[#fff]"></i>
                                        </a>
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                        <a
                                            href="javascript:void(0)"
                                            class="model-oraganic-product m-[0] p-[0] bg-transparent cr-modal-toggle cursor-pointer"
                                            role="button"
                                            tabindex="0"
                                            @click="is_buy_now=0; addToCompare({{ $product->id }})"
                                        >
                                            <i class="ri-eye-line transition-all duration-[0.3s] ease-in-out h-[40px] w-[40px] mr-[10px] flex items-center justify-center text-[22px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] hover:bg-[#64b496] hover:text-[#fff]"></i>
                                        </a>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>
                            </div>

                            <!-- Buy Now Button -->
                            {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                            @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                <x-licious::button
                                    type="submit"
                                    class="primary-button w-full max-w-[470px] mt-5"
                                    button-type="secondary-button"
                                    :title="trans('shop::app.products.view.buy-now')"
                                    :disabled="! $product->isSaleable(1)"
                                    ::loading="isStoring.buyNow"
                                    @click="is_buy_now=1;"
                                />
                            @endif

                            {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}

                        </form>
                    </x-licious::form>

                    {!! view_render_event('bagisto.shop.products.view.additional_actions.before', ['product' => $product]) !!}
                    <!-- Share Buttons -->
                    <div class="flex gap-9 mt-10 max-sm:flex-wrap max-sm:justify-center">

                    </div>

                    {!! view_render_event('bagisto.shop.products.view.additional_actions.after', ['product' => $product]) !!}
                </div>

            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product', {
            template: '#v-product-template',

            props: ['productId'],

            data() {
                return {
                    isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    is_buy_now: 0,

                    isStoring: {
                        addToCart: false,

                        buyNow: false,
                    },
                }
            },

            methods: {
                addToCart(params) {
                    const operation = this.is_buy_now ? 'buyNow' : 'addToCart';

                    this.isStoring[operation] = true;

                    let formData = new FormData(this.$refs.formData);

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                if (response.data.redirect) {
                                    window.location.href= response.data.redirect;
                                }
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isStoring[operation] = false;
                        })
                        .catch(error => {
                            this.isStoring[operation] = false;
                        });
                },

                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                product_id: "{{ $product->id }}"
                            })
                            .then(response => {
                                this.isWishlist = ! this.isWishlist;

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
                    let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                    if (existingItems.length) {
                        if (! existingItems.includes(productId)) {
                            existingItems.push(productId);

                            this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                        }
                    } else {
                        this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                    }
                },

                getCompareItemsStorageKey() {
                    return 'compare_items';
                },

                setStorageValue(key, value) {
                    localStorage.setItem(key, JSON.stringify(value));
                },

                getStorageValue(key) {
                    let value = localStorage.getItem(key);

                    if (value) {
                        value = JSON.parse(value);
                    }

                    return value;
                },
            },
        });
    </script>
@endPushOnce
