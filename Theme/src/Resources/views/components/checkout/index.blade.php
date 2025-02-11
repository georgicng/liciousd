<v-checkout>
    <!-- Shimmer Effect -->
    <x-licious::shimmer.checkout.onepage />
</v-checkout>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-template"
    >
        <template v-if="! cart">
            <!-- Shimmer Effect -->
            <x-licious::shimmer.checkout.onepage />
        </template>

        <template v-else>
            <div class="grid grid-cols-[1fr_auto] gap-8 max-lg:grid-cols-[1fr]">
                <div
                    class="overflow-y-auto"
                    id="steps-container"
                >
                    <!-- Included Addresses Blade File -->
                    <template v-if="['address', 'shipping', 'payment', 'review'].includes(currentStep)">
                        @include('shop::checkout.onepage.address')
                    </template>

                    <!-- Included Shipping Methods Blade File -->
                    <template v-if="cart.have_stockable_items && ['shipping', 'payment', 'review'].includes(currentStep)">
                        @include('shop::checkout.onepage.shipping')
                    </template>

                    <!-- Included Payment Methods Blade File -->
                    <template v-if="['payment', 'review'].includes(currentStep)">
                        @include('shop::checkout.onepage.payment')
                    </template>
                </div>

                <!-- Included Checkout Summary Blade File -->
                <div class="sticky top-8 h-max w-[442px] max-w-full ltr:pl-8 rtl:pr-8 max-lg:w-auto max-lg:max-w-[442px] max-lg:ltr:pl-0 max-lg:rtl:pr-0">
                    @include('shop::checkout.onepage.summary')

                    <div
                        class="flex justify-end"
                        v-if="canPlaceOrder"
                    >
                        <template v-if="cart.payment_method == 'paypal_smart_button'">
                            {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.before') !!}

                            <v-paypal-smart-button></v-paypal-smart-button>

                            {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.after') !!}
                        </template>

                        <template v-else>
                            <x-licious::button
                                type="button"
                                class="primary-button w-max py-3 px-11 bg-navyBlue rounded-2xl max-sm:text-sm max-sm:px-6 max-sm:mb-10"
                                :title="trans('shop::app.checkout.onepage.summary.place-order')"
                                ::disabled="isPlacingOrder"
                                ::loading="isPlacingOrder"
                                @click="placeOrder"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-checkout', {
            template: '#v-checkout-template',

            data() {
                return {
                    cart: null,

                    isCartLoading: true,

                    isPlacingOrder: false,

                    currentStep: 'address',

                    shippingMethods: null,

                    paymentMethods: null,

                    canPlaceOrder: false,
                }
            },

            mounted() {
                this.getCart();
            },

            methods: {
                getCart() {
                    this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                        .then(response => {
                            this.cart = response.data.data;

                            this.isCartLoading = false;

                            this.scrollToCurrentStep();
                        })
                        .catch(error => {});
                },

                stepForward(step) {
                    this.currentStep = step;

                    if (step == 'review') {
                        this.canPlaceOrder = true;

                        return;
                    }

                    this.canPlaceOrder = false;

                    if (this.currentStep == 'shipping') {
                        this.shippingMethods = null;
                    } else if (this.currentStep == 'payment') {
                        this.paymentMethods = null;
                    }
                },

                stepProcessed(data) {
                    if (this.currentStep == 'shipping') {
                        this.shippingMethods = data;
                    } else if (this.currentStep == 'payment') {
                        this.paymentMethods = data;
                    }

                    this.getCart();
                },

                scrollToCurrentStep() {
                    let container = document.getElementById('steps-container');

                    if (! container) {
                        return;
                    }

                    container.scrollIntoView({
                        behavior: 'smooth',
                        block: 'end'
                    });
                },

                placeOrder() {
                    this.isPlacingOrder = true;

                    this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                        .then(response => {
                            if (response.data.data.redirect) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                            }

                            this.isPlacingOrder = false;
                        })
                        .catch(error => {
                            this.isPlacingOrder = false

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                }
            },
        });
    </script>
@endPushOnce
