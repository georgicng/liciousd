<v-filters {{ $attributes }}>
    <!-- Category Filter Shimmer Effect -->
    <x-licious::shimmer.categories.filters />
</v-filters>

@pushOnce('scripts')
<script type="text/x-template" id="v-filters-template">
    <!-- Filters Vue template -->
    <template v-for='(filter, filterIndex) in filters.available' :key="filter.id">
        <!-- Filters Items Vue Component -->
        <x-licious::categories.filters.item
            ::class="[ `cr-shop-${filter.code}`, filterIndex === 0? 'pt-[25px]' : '' ]"
            ::filter="filter"
            ::value="filters.applied[filter.code]"
            ::allowed-Max-Price="allowedMaxPrice"            
            @facet="$emit('filter', { filter, values: $event })"
        />
    </template>
</script>

<script type='module'>
    app.component('v-filters', {
        template: '#v-filters-template',
        props: {
            filters: {
                type: Object, 
                default: () => ({
                    available: {},

                    applied: {},
                })
            },
            allowedMaxPrice: {
                type: Number,
                default: 100
            }
        }
    });
</script>
@endPushOnce
