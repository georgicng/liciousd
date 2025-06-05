<v-price-filter {{ $attributes }}></v-price-filter>

@pushOnce('scripts')
<script type="text/x-template" id="v-price-filter-template">
    <x-licious::range-slider
        default-type="price"
        ::allowed-max-range="allowedMaxPrice"
        ::min-range="minRange"
        ::max-range="maxRange"
        @change-range="setPriceRange"
    />
</script>

<script type='module'>
    app.component('v-price-filter', {
        template: '#v-price-filter-template',

        props: ['value', 'allowedMaxPrice'],

        computed: {
            minRange() {
                let priceRange = this.value.split(',');

                return parseInt(priceRange[0]);
            },

            maxRange() {
                let priceRange = this.value.split(',');

                return parseInt(priceRange[1]);
            }
        },

        methods: {
            setPriceRange($event) {
                this.$emit('slide', [$event.minRange, $event.maxRange].join(','));
            },
        }
    });
</script>
@endPushOnce
