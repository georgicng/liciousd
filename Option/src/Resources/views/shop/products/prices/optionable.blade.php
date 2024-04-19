

<v-changeable-price />

@pushOnce('scripts')
<script type="text/x-template" id="v-changeable-price-template">
    <template v-if="onSale">
        <p class="font-medium text-[#6E6E6E] line-through">
            @{{ format(salePrice) }}
        </p>

        <p class="font-semibold">
            @{{ format(regularPrice) }}
        </p>
    </template>
    <template v-else>
        <p class="font-semibold">
            @{{ format(regularPrice) }}
        </p>
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
