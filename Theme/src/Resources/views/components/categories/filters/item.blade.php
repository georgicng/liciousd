<v-filter-item {{ $attributes }}></v-filter-item>

@pushOnce('scripts')
    <script type="text/x-template" id="v-filter-item-template">
        <!-- Filter Item Vue template -->
        <div  v-if="filter.type == 'price' || filter.options.length">
            <!-- Filter Item Header -->
            <h4
                class="cr-shop-sub-title mb-[0] pb-[10px] font-Poppins text-[16px] font-medium leading-[1.6] text-[#2b2b2d] capitalize border-b-[1px] border-solid border-[#e9e9e9] max-[991px]:text-[18px]"
                v-text="filter.name"></h4>


            <!-- Price Range Filter -->
            <template v-if="filter.type !== 'price'">
                <x-licious::categories.filters.type.checkbox ::options="filter.options" ::value="value" @check="applyValue" />
            </template>

            <!-- Checkbox Filter -->
            <template v-else>
                <x-licious::categories.filters.type.price ::value="value" ::allowed-Max-Price="allowedMaxPrice" @slide="applyValue" />
            </template>
        </div>
    </script>

    <script type='module'>
        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter', 'value', 'allowedMaxPrice'],

            methods: {
                applyValue(value) {
                    this.$emit('facet', value);
                },
            },
        });
    </script>
@endPushOnce
