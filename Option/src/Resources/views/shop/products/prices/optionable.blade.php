

<v-changeable-price />

@pushOnce('scripts')
<script type="text/x-template" id="v-changeable-price-template">
    <template v-if="onSale">
        <p class="font-medium text-[#6E6E6E] line-through">
            @{{ salePrice }}
        </p>

        <p class="font-semibold">
            @{{ regularPrice }}
        </p>
    </template>
    <template v-else>
        <p class="font-semibold">
            @{{ regularPrice }}
        </p>
    </template>
</script>

<script type="module">
    app.component("v-changeable-price", {
        template: '#v-changeable-price-template',

        data() {
            const price = @json($prices);
            console.log({ price })
            return  {
                price,
                increment: 0,
            }
        },

        computed: {
            onSale() {
                return this.price['final']['price'] < this.price['regular']['price']
            },
            salePrice() {
                return this.onSale && this.price['final']['formatted_price']  + this.increment ;
            },
            regularPrice() {
                return this.price['regular']['formatted_price']  + this.increment ;
            }
        },

        methods: {
            registerGlobalEvents() {
                this.$emitter.on('update-price', this.update);
            },
            update(increment) {
                this.increment = increment;
            }
        },

        created() {
            this.registerGlobalEvents();
        }
    });
</script>
@endpushOnce