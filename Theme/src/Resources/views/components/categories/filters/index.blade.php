<v-filters {{ $attributes }}>
    <!-- Category Filter Shimmer Effect -->
    <x-licious::shimmer.categories.filters />
</v-filters>

@pushOnce('scripts')
<script type="text/x-template" id="v-filters-template">
    <!-- Filters Vue template -->
    <template v-for='(filter, filterIndex) in filters.available' :key="filterIndex">
        <!-- Filters Items Vue Component -->
        <x-licious::categories.filters.item
            ::class="[ `cr-shop-${filter.code}`, filterIndex === 0? 'pt-[25px]' : '' ]"
            ref="filterItemComponent"
            ::filter="filter"
            @values-applied="applyFilter(filter, $event)" />
    </template>
</script>

<script type='module'>
    app.component('v-filters', {
        template: '#v-filters-template',

        data() {
            return {
                isLoading: true,

                filters: {
                    available: {},

                    applied: {},
                },
            };
        },

        mounted() {
            this.getFilters();

            this.setFilters();
        },

        methods: {
            getFilters() {
                this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                        params: {
                            category_id: "{{ isset($category) ? $category->id : ''  }}",
                        }
                    })
                    .then((response) => {
                        this.isLoading = false;

                        this.filters.available = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            setFilters() {
                let queryParams = new URLSearchParams(window.location.search);

                queryParams.forEach((value, filter) => {
                    /**
                     * Removed all toolbar filters in order to prevent key duplication.
                     */
                    if (!['sort', 'limit', 'mode'].includes(filter)) {
                        this.filters.applied[filter] = value.split(',');
                    }
                });

                this.$emit('filter-applied', this.filters.applied);
            },

            applyFilter(filter, values) {
                if (values.length) {
                    this.filters.applied[filter.code] = values;
                } else {
                    delete this.filters.applied[filter.code];
                }

                this.$emit('filter-applied', this.filters.applied);
            },

            clear() {
                /**
                 * Clearing parent component.
                 */
                this.filters.applied = {};

                /**
                 * Clearing child components. Improvisation needed here.
                 */
                this.$refs.filterItemComponent.forEach((filterItem) => {
                    if (filterItem.filter.code === 'price') {
                        filterItem.$data.appliedValues = null;
                    } else {
                        filterItem.$data.appliedValues = [];
                    }
                });

                this.$emit('filter-applied', this.filters.applied);
            },
        },
    });
</script>
@endPushOnce
