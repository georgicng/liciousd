<div class="cr-sidebar-wrap border-[1px] border-solid border-[#e9e9e9] mb-[30px] p-[15px] bg-[#fff] rounded-[5px]">
    <div class="cr-sidebar-block">
        <div class="cr-sb-title">
            {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

            <p
                class="text-2xl font-medium"
                role="heading"
            >
                @lang('licious::app.checkout.cart.summary.cart-summary')
            </p>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}
        </div>
        <div class="cr-sb-block-content mb-[0] mt-[15px]">
            <div class="cr-checkout-summary">

                <!-- Cart Totals -->
                <div class="flex justify-between items-center mb-[10px]">
                    <!-- Sub Total -->
                    {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

                    <span class="text-left text-[#7a7a7a] text-[14px] leading-[24px] tracking-[0]">
                        @lang('licious::app.checkout.cart.summary.sub-total')
                    </span>

                    <span class="text-right text-[#000] text-[15px] leading-[24px] font-medium">
                        @{{ cart.formatted_sub_total }}
                    </span>
                    {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}
                </div>



                <!-- Taxes -->
                {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.before') !!}

                <div
                    class="flex justify-between items-center mb-[10px]"
                    v-for="(amount, index) in cart.base_tax_amounts"
                    v-if="parseFloat(cart.base_tax_total)"
                >
                    <span class="text-left text-[#7a7a7a] text-[14px] leading-[24px] tracking-[0]">
                        @lang('licious::app.checkout.cart.summary.tax') (@{{ index }})%
                    </span>

                    <span class="text-right text-[#000] text-[15px] leading-[24px] font-medium">
                        @{{ amount }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.after') !!}

                <!-- Discount -->
                {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

                <div
                    class="flex justify-between items-center mb-[10px]"
                    v-if="cart.base_discount_amount && parseFloat(cart.base_discount_amount) > 0"
                >
                    <span class="text-left text-[#7a7a7a] text-[14px] leading-[24px] tracking-[0]">
                        @lang('licious::app.checkout.cart.summary.discount-amount')
                    </span>

                    <span class="text-right text-[#000] text-[15px] leading-[24px] font-medium">
                        @{{ cart.formatted_base_discount_amount }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

                <!-- Shipping Rates -->
                {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

                <div
                    class="flex justify-between items-center mb-[10px]"
                    v-if="cart.selected_shipping_rate"
                >
                    <span class="text-left text-[#7a7a7a] text-[14px] leading-[24px] tracking-[0]">
                        @lang('licious::app.checkout.onepage.summary.delivery-charges')
                    </span>

                    <span class="text-right text-[#000] text-[15px] leading-[24px] font-medium">
                        @{{ cart.selected_shipping_rate }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

                <!-- Apply Coupon -->
                {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}

                <x-licious::coupon ::cart="cart"  @coupon-applied="getCart" @coupon-removed="getCart" />

                {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}

                <!-- Cart Grand Total -->
                {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

                <div class="cr-checkout-summary-total flex justify-between items-center mb-[0] border-t-[1px] border-solid border-[#e9e9e9] pt-[19px] mt-[16px]">
                    <span class="text-left font-Manrope text-[16px] font-semibold text-[#2b2b2d] leading-[24px] tracking-[0]">
                        @lang('licious::app.checkout.cart.summary.grand-total')
                    </span>

                    <span class="text-right font-Manrope text-[16px] font-semibold text-[#2b2b2d] leading-[24px] tracking-[0]">
                        @{{ cart.formatted_grand_total }}
                    </span>
                </div>

                {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}
            </div>
        </div>
    </div>
</div>
 {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}
    <span class="cr-check-order-btn flex justify-end p-[0]">
        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
            >@lang('licious::app.checkout.cart.summary.proceed-to-checkout')</a>
    </span>
{!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}
