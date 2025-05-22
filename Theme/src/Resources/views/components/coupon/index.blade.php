<!-- Coupon Vue Component -->
<v-coupon {{ $attributes }}>
</v-coupon>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-coupon-template"
    >
        <div class="flex justify-between items-center mb-[10px]">
            <span class="text-left text-[#7a7a7a] text-[14px] leading-[24px] tracking-[0]">
                @{{ cart.coupon_code ? "@lang('shop::app.checkout.cart.coupon.applied')" : "@lang('shop::app.checkout.cart.coupon.discount')" }}
            </span>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.before') !!}

            <p class="text-right text-[#000] text-[15px] leading-[24px] font-medium">
                <!-- Apply Coupon Form -->
                <x-licious::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Apply coupon form -->
                    <form @submit="handleSubmit($event, applyCoupon)">
                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.before') !!}

                        <!-- Apply coupon modal -->
                        <x-licious::modal ref="couponModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <span
                                    class="text-right text-[#000] text-[15px] leading-[24px] font-medium"
                                    role="button"
                                    tabindex="0"
                                    v-if="! cart.coupon_code"
                                >
                                    @lang('licious::app.checkout.cart.coupon.apply')
                                </span>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header>
                                <h2 class="text-2xl font-medium max-sm:text-xl">
                                    @lang('licious::app.checkout.cart.coupon.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <x-licious::form.control-group class="!mb-0">
                                    <x-licious::form.control-group.control
                                        type="text"
                                        class="py-5 px-6"
                                        name="code"
                                        rules="required"
                                        :placeholder="trans('shop::app.checkout.cart.coupon.enter-your-code')"
                                    />

                                    <x-licious::form.control-group.error
                                        class="flex"
                                        control-name="code"
                                    />
                                </x-licious::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Coupon Form Action Container -->
                                <div class="flex items-center gap-4 flex-wrap">
                                    <div class="flex gap-4 items-center">
                                        <p class="text-sm font-medium text-[#6E6E6E]">
                                            @lang('licious::app.checkout.cart.coupon.subtotal')
                                        </p>

                                        <p class="text-3xl font-semibold max-sm:text-xl">
                                            @{{ cart.formatted_sub_total }}
                                        </p>
                                    </div>

                                    <x-licious::button
                                        class="flex-auto max-w-none cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
                                        :title="trans('shop::app.checkout.cart.coupon.button-title')"
                                        ::loading="isStoring"
                                        ::disabled="isStoring"
                                    />
                                </div>
                            </x-slot>
                        </x-licious::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.after') !!}
                    </form>
                </x-licious::form>

                <!-- Applied Coupon Information Container -->
                <div
                    class="flex justify-between items-center text-xs font-small "
                    v-if="cart.coupon_code"
                >
                    <p
                        class="text-base font-medium text-navyBlue"
                        title="@lang('shop::app.checkout.cart.coupon.applied')"
                    >
                        "@{{ cart.coupon_code }}"
                    </p>

                    <span
                        class="ri-close-line text-2xl cursor-pointer"
                        title="@lang('shop::app.checkout.cart.coupon.remove')"
                        @click="destroyCoupon"
                    >
                    </span>
                </div>
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',

            props: ['cart'],

            data() {
                return {
                    isStoring: false,
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('shop.api.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.$emit('coupon-applied');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.couponModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            this.$refs.couponModel.toggle();

                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                resetForm();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$emit('coupon-removed');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce
