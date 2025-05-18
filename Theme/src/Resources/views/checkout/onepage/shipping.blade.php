{!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

<v-shipping-methods
    :methods="shippingMethods"
    :active="cart?.selected_shipping_rate_method"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Shipping Method Shimmer Effect -->
    <x-licious::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="mb-7">
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-licious::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <!-- Accordion Blade Component -->
                <x-licious::accordion class="!border-b-0">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="!py-4 !px-0">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-medium max-sm:text-xl">
                                @lang('licious::app.checkout.onepage.shipping.shipping-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
                    <x-slot:content class="!p-0 mt-8">
                        <div class="flex flex-wrap gap-8">
                            <div
                                class="relative max-w-[218px] max-sm:max-w-full max-sm:flex-auto select-none"
                                v-for="method in methods"
                            >
                                {!! view_render_event('bagisto.shop.checkout.onepage.shipping.before') !!}

                                <div v-for="rate in method.rates">
                                    <input
                                        type="radio"
                                        name="shipping_method"
                                        :id="rate.method"
                                        :value="rate.method"
                                        class="hidden peer"
                                        @change="store(rate.method)"
                                    >

                                    <label
                                        class=" text-2xl text-navyBlue  cursor-pointer"
                                        :class="{ 'ri-checkbox-circle-line': rate.method === selectedMethod, 'ri-checkbox-blank-circle-line': rate.method !== selectedMethod }"
                                        :for="rate.method"
                                    >
                                        <label
                                            class="block p-5 border border-[#E9E9E9] rounded-xl cursor-pointer"
                                            :for="rate.method"
                                        >
                                            <span class="ri-hand-coin-line text-6xl text-navyBlue"></span>

                                            <p class="text-2xl mt-1.5 font-semibold max-sm:text-xl">
                                                @{{ rate.base_formatted_price }}
                                            </p>

                                            <p class="text-xs mt-2.5 font-medium">
                                                <span class="font-medium">@{{ rate.method_title }}</span>
                                            </p>
                                        </label>
                                    </label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.shipping-method.after') !!}
                            </div>
                        </div>
                    </x-slot>
                </x-licious::accordion>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
                active: {
                    type: String,
                    default: null,
                },
            },
            data() {
                return {
                    selectedMethod: null
                }
            },
            computed: {
                methodMap() {
                    return this.methods.reduce((acc, item) => ({ ...acc, [item.method_title]: item.method  }), {})
                }
            },
            watch: {
                active(newVal) {
                    if (!newVal) {
                        return;
                    }
                    this.selectedMethod = this.methodMap[newVal];
                }
            },

            emits: ['processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.selectedMethod = selectedMethod;
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
