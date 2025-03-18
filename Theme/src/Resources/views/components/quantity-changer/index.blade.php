@props([
    'name'  => '',
    'value' => 1,
    'type' => 'product',
])

<v-quantity-changer  name="{{ $name }}" value="{{ $value }}">
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        @if ($type === 'product')
            <div class="cr-qty-main h-full flex relative">
                <input type="text" :name="name" placeholder="." v-model="quantity" minlength="1" maxlength="20" class="quantity h-[40px] w-[40px] mr-[5px] text-center border-[1px] border-solid border-[#e9e9e9] rounded-[5px]">
                <button type="button" class="plus w-[18px] h-[18px] p-[0] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] leading-[0]" @click="increase">+</button>
                <button type="button" class="minus w-[18px] h-[18px] p-[0] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] leading-[0] absolute bottom-[0] right-[0]" @click="decrease">-</button>
            </div>
        @else
            <div
                class="cart-qty-plus-minus m-[0] w-[80px] h-[30px] relative overflow-hidden flex bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] items-center justify-between">
                <button
                    type="button"
                    class="plus h-[25px] w-[25px] mt-[-2px] border-[0] bg-transparent flex justify-center items-center"
                    tabindex="0"
                    aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                    @click="increase">+</button>
                <input
                    type="text"
                    :name="name"
                    placeholder="."
                    v-model="quantity"
                    minlength="1"
                    maxlength="20"
                    class="quantity w-[30px] m-[0] p-[0] text-[#444] float-left text-[14px] font-semibold leading-[38px] h-auto text-center outline-[0]">
                <button
                    type="button"
                    class="minus h-[25px] w-[25px] mt-[-2px] border-[0] bg-transparent flex justify-center items-center"
                    tabindex="0"
                    aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                    @click="decrease">-</button>
            </div>
        @endif
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value'],

            data() {
                return  {
                    quantity: this.value,
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
            },

            methods: {
                increase() {
                    this.$emit('change', ++this.quantity);
                },

                decrease() {
                    if (this.quantity > 1) {
                        this.quantity -= 1;
                    }

                    this.$emit('change', this.quantity);
                },
            }
        });
    </script>
@endpushOnce
