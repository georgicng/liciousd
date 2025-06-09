@props(['type' => 'desktop'])

<!-- Mini Cart Vue Component -->
<v-filter-drawer>
    <a href="javascript:void(0)" class="shop_side_view h-[35px] w-[35px] flex justify-center items-center mr-[7px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] max-[360px]:mr-[7px]">
        <i class="ri-filter-line text-[20px]"></i>
    </a>
</v-filter-drawer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-filter-drawer-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        <x-licious::drawer ref="filterDrawer" isActive="false" class="cr-shop-leftside">
            <!-- Drawer Toggler -->
            <x-slot:toggle>
                <a href="javascript:void(0)" @click.prevent="open" class="shop_side_view h-[35px] w-[35px] flex justify-center items-center mr-[7px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] max-[360px]:mr-[7px]">
                    <i class="ri-filter-line text-[20px]"></i>
                </a>
            </x-slot>

            <x-slot:overlay>
                <div v-if="isOpen" class="filter-sidebar-overlay w-full h-screen fixed z-[20] top-[0] left-[0] bg-[#000000b3]"></div>
            </x-slot>

            <!-- Drawer Content -->
            <div class="cr-shop-leftside-inner w-full h-full flex flex-col justify-between">
                <div>
                    <div class="cr-title p-[15px] flex flex-row justify-between items-center">
                        <h6 class="m-[0] text-[17px] font-bold text-[#2b2b2d] leading-[1.2]">@lang('licious::app.categories.filters.filter')</h6>
                        <a href="javascript:void(0)" class="close-shop-leftside text-[#fb5555]" @click="close">
                            <i class="ri-close-line text-[22px]"></i>
                        </a>
                    </div>
                    <template v-if="isLoading">
                        <x-licious::shimmer.categories.filters />                        
                    </template>
                    <div v-else class="cr-shop-sideview p-[24px] bg-[#f7f7f8] border-[1px] border-solid border-[#e9e9e9] rounded-[0] sticky top-[30px]">
                        <x-licious::categories.filters ::allowed-Max-Price="allowedMaxPrice" ::filters="filters" @filter="setFilter($event)" />
                    </div>
                </div>
                <div class="relative top-[-20px] border-t-[1px] border-solid border-[#e9e9e9] p-4 flex justify-between">
                    <button
                        class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
                        tabindex="0"
                        @click="applyFilters(close)"
                    >@lang('licious::app.categories.filters.apply')</button>
                    <button
                        class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
                        tabindex="0"
                        @click="clear(close)"
                    >@lang('licious::app.categories.filters.clear-all')</button>
                </div>
            </div>
        </x-licious::drawer>

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component("v-filter-drawer", {
            template: '#v-filter-drawer-template',
            inject: ['setFilters', 'clearFilters'],

            data() {
                return {
                    isLoading: true,
                    allowedMaxPrice: 100,
                    filters: {
                        available: {},
                        applied: {},
                    },
                };
            },
            computed: {
                facets() {
                    return this.filters.available.reduce((acc, filter) => ({...acc, [filter.code]: filter}), {});
                },
            },

            methods: {
                getMaxPrice() {
                    this.$axios.get(`{{ route("shop.api.categories.max_price", $category->id ?? '') }}`)
                        .then((response) => {
                            /**
                             * If data is zero, then default price will be displayed.
                             */
                            if (response.data.data.max_price) {
                                this.allowedMaxPrice = parseInt(response.data.data.max_price);
                                this.filters.applied.price = this.allowedMaxPrice? `0,${this.allowedMaxPrice}` : '0,100';
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                getFilters() {
                    this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                            params: {
                                category_id: "{{ isset($category) ? $category->id : ''  }}",
                            }
                        })
                        .then((response) => {
                            this.filters.available = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                },
                initFilters() {
                    let queryParams = new URLSearchParams(window.location.search);
                    queryParams.forEach((value, filter) => {
                        /**
                         * Removed all toolbar filters in order to prevent key duplication.
                         */
                        if (!['sort', 'limit', 'mode'].includes(filter)) {
                            this.filters.applied[filter] = value.split(',');
                        }
                    });
                    this.setFilters('filter', {...this.filters.applied});
                },

                setFilter({filter, values}) {
                    if (values?.length) {
                        this.filters.applied[filter.code] = values;
                    } else {
                        delete this.filters.applied[filter.code];
                    }
                },

                applyFilters(cb) {
                    this.setFilters('filter', {...this.filters.applied});
                    cb && cb();
                },

                clear(cb) {
                    this.filters.applied = { price: this.allowedMaxPrice? `0,${this.allowedMaxPrice}` : '0,100'};
                },
            },
            mounted() {
                Promise.all([this.getFilters(), this.getMaxPrice(), this.initFilters()]).then(() => {
                    this.isLoading = false;
                })
            },
        });
    </script>
@endpushOnce
