

<v-changeable-price />

@pushOnce('scripts')
<script type="text/x-template" id="v-changeable-price-template">
    <template v-if="onSale">
        <span class="new-price font-Poppins text-[24px] font-semibold leading-[1.167] text-[#64b496] max-[767px]:text-[22px] max-[575px]:text-[20px]">
            @{{ format(salePrice) }}
        </span>

        <span class="old-price font-Poppins text-[16px] line-through leading-[1.75] text-[#7a7a7a]">
            @{{ format(regularPrice) }}
        </span>
    </template>
    <template v-else>
        <span class="new-price font-Poppins text-[24px] font-semibold leading-[1.167] text-[#64b496] max-[767px]:text-[22px] max-[575px]:text-[20px]">
            @{{ format(regularPrice) }}
        </span>
    </template>
</script>

<script type="module">
    app.component("v-changeable-price", {
        template: '#v-changeable-price-template',

        data() {
            const price = @json($prices);
            const currency = @json($currency);
            return  {
                price,
                currency,
                increment: 0,
            }
        },

        computed: {
            onSale() {
                return this.price['final']['price'] < this.price['regular']['price']
            },
            salePrice() {
                return this.onSale && this.price['final']['price']  + this.increment ;
            },
            regularPrice() {
                return this.price['regular']['price']  + this.increment ;
            }
        },

        methods: {
            registerGlobalEvents() {
                this.$emitter.on('update-price', this.update);
            },
            update(increment) {
                this.increment = increment;
            },
            format (number ) {
                return this.$shop.formatPrice(number)
                .replace(this.currency.code, this.currency.symbol)
                .trim();
            }
        },

        created() {
            this.registerGlobalEvents();
        }
    });
</script>
@endpushOnce
