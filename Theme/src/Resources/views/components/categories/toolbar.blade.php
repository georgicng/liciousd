{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar {{ $attributes }} />

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , 'Webkul\Product\Helpers\Toolbar')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id='v-toolbar-template'
    >
        <div class="w-full px-[12px]">
            <div class="cr-shop-bredekamp mb-[30px] flex flex-wrap items-center bg-[#f7f7f8] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]">
                {!! view_render_event('bagisto.shop.categories.toolbar.filter.before') !!}
                <!-- Listing Mode Switcher -->
                <div class="cr-toggle m-[5px] flex">
                    <x-licious::drawer.filter @filter="$emit('filter-action', $event)" @clear="$emit('clear-action', $event)" />
                    <a
                        href="javascript:void(0)"
                        role="button"
                        aria-label="@lang('shop::app.categories.toolbar.grid')"
                        tabindex="0"
                        class="gridCol h-[35px] w-[35px] flex justify-center items-center mr-[7px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] max-[360px]:mr-[7px] active-grid"
                        :class="{ 'active-grid': filters.applied.mode === 'grid' }"
                        @click="changeMode('list')">
                        <i class="ri-grid-line text-[20px]"></i>
                    </a>
                    <a
                        href="javascript:void(0)"
                        role="button"
                        aria-label="@lang('shop::app.categories.toolbar.list')"
                        tabindex="0"
                        class="gridRow h-[35px] w-[35px] flex justify-center items-center bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]"
                        :class="{ 'active-grid': filters.applied.mode === 'list' }"
                        @click="changeMode('grid')"
                        >
                        <i class="ri-list-check-2 text-[20px]"></i>
                    </a>
                </div>

                <div class="center-content flex justify-start items-center flex-[1]">
                    <span class="px-[12px] font-Poppins text-[14px] leading-[1.875] text-[#7a7a7a] max-[767px]:hidden">We found 29 items for you!</span>
                </div>

                <!-- Product Sorting Filters -->
                <div class="cr-select h-[35px] m-[5px] pt-[3px] pr-[0] pb-[3px] pl-[15px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] flex max-[360px]:pl-[10px]">
                    <label class="font-Poppins text-[15px] leading-[1.7] text-[#7a7a7a] inline-block max-[767px]:leading-[2.2] max-[767px]:text-[12px]">@{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }} :</label>
                    <select
                        class="form-select py-[0px] px-[6px] mr-[10px] tracking-[0] font-Poppins text-[15px] bg-[10px] leading-[1.2] text-[#7a7a7a] w-[auto] border-[0] outline-[0] block cursor-pointer max-[767px]:text-[12px]"
                        aria-label="Default select example"
                        :value="filters.applied.sort"
                        >
                        <option
                            v-for="(sort, key) in filters.available.sort"
                            :key="key"
                            :value="sort.value"
                            @click="apply('sort', sort.value)">@{{ sort.title }}</option>

                    </select>
                </div>
                {!! view_render_event('bagisto.shop.categories.toolbar.filter.after') !!}


            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        default: {
                            sort: '{{ $toolbar->getOrder([])['value'] }}',

                            limit: '{{ $toolbar->getLimit([]) }}',

                            mode: '{{ $toolbar->getMode([]) }}',
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder($params ?? [])['value'] }}',

                            limit: '{{ $toolbar->getLimit($params ?? []) }}',

                            mode: '{{ $toolbar->getMode($params ?? []) }}',
                        }
                    }
                };
            },

            mounted() {
                this.setFilters();
            },

            computed: {
                sortLabel() {
                    return this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort).title;
                }
            },

            methods: {
                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.setFilters();
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.setFilters();
                },

                setFilters() {
                    let filters = {};

                    for (let key in this.filters.applied) {
                        if (this.filters.applied[key] != this.filters.default[key]) {
                            filters[key] = this.filters.applied[key];
                        }
                    }
                    console.log({ 'tool-action': filters})
                    this.$emit('tool-action', filters);
                }
            },
        });
    </script>
@endPushOnce
