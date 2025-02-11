<v-filter-item {{ $attributes }}></v-filter-item>

@pushOnce('scripts')
    <!-- Filter Item Vue template -->
    <script type="text/x-template" id="v-filter-item-template">
        <template v-if="filter.options.length">
            <div>
                <!-- Filter Item Header -->
                <h4
                    class="cr-shop-sub-title mb-[0] pb-[10px] font-Poppins text-[16px] font-medium leading-[1.6] text-[#2b2b2d] capitalize border-b-[1px] border-solid border-[#e9e9e9] max-[991px]:text-[18px]"
                    v-text="filter.name"></h4>


                <!-- Price Range Filter -->
                <template v-if="filter.type === 'price'">
                    <x-licious::categories.filters.type.price ::key="refreshKey" ::default-price-range="appliedValues" @set-price-range="applyValue($event)" />
                </template>

                <!-- Checkbox Filter -->
                <template v-else>
                    <x-licious::categories.filters.type.checkbox ::options="filter.options" @change="applyValue" />
                </template>
            </div>
        </template>
    </script>

    <script type='module'>
        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter'],

            data() {
                return {
                    active: true,

                    appliedValues: null,

                    refreshKey: 0,
                }
            },

            watch: {
                appliedValues() {
                    if (this.filter.code === 'price' && ! this.appliedValues) {
                        ++this.refreshKey;
                    }
                },
            },

            mounted() {
                if (this.filter.code === 'price') {
                    /**
                     * Improvisation needed here for `this.$parent.$data`.
                     */
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code]?.join(',');

                    ++this.refreshKey;

                    return;
                }

                /**
                 * Improvisation needed here for `this.$parent.$data`.
                 */
                this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];
            },

            methods: {
                applyValue($event) {
                    this.appliedValues = $event;
                    this.$emit('values-applied', $event);
                },
            },
        });
    </script>
@endPushOnce
