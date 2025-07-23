@props(['category' => null])

<v-category class="w-full" {{ $attributes }}>
    <!-- Category Shimmer Effect -->
    <x-licious::shimmer.categories.view />
</v-category>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-category-template"
    >
        <div class="flex flex-wrap w-full">

            <!-- Product Listing Container -->
            <div class="w-full" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                <!-- Desktop Product Listing Toolbar -->
                <div class="flex flex-wrap w-full">
                    <x-licious::categories.toolbar ::meta="meta" @tool-action="setFilters('toolbar', $event)" />
                </div>

                <!-- Product List Card Container -->
                <div
                    class="flex flex-wrap col-50 mb-[-24px]"
                    :class="{'col-size': mode == 'list' }"
                >
                    <!-- Product Card Shimmer Effect -->
                    <template v-if="isLoading">
                        <template v-if="mode == 'grid'" >
                            <x-licious::shimmer.products.cards.grid  count="8" />
                        </template>
                        <template v-else>
                            <x-licious::shimmer.products.cards.list count="8" />
                        </template>
                    </template>

                    <!-- Product Card Listing -->
                    {!! view_render_event('bagisto.shop.categories.view.list.product_card.before') !!}

                    <template v-else>
                        <template v-if="products?.length">
                            <x-licious::products.card
                                ::mode="mode"
                                v-for="product in products"
                                ::product="product"
                                ::key="product.id"
                            />
                        </template>

                        <!-- Empty Products Container -->
                        <template v-else>
                            <div class="grid items-center justify-items-center place-content-center w-full m-auto h-[476px] text-center">
                                <img
                                    src="{{ bagisto_asset('images/thank-you.png') }}"
                                    alt="@lang('licious::app.categories.view.empty')"
                                />

                                <p
                                    class="text-xl"
                                    role="heading"
                                >
                                    @lang('licious::app.categories.view.empty')
                                </p>
                            </div>
                        </template>
                    </template>

                    {!! view_render_event('bagisto.shop.categories.view.list.product_card.after') !!}
                </div>

                <x-licious::categories.pagination v-if="meta.total > meta.per_page" ::loading="loading" ::meta="meta" @goto="setPage" />
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-category', {
            template: '#v-category-template',

            data() {
                return {
                    isMobile: window.innerWidth <= 767,

                    isLoading: true,

                    isDrawerActive: {
                        toolbar: false,

                        filter: false,
                    },

                    filters: {
                        toolbar: {},

                        filter: {},

                        page: 1
                    },

                    products: [],

                    links: {},
                    meta: {},

                    loader: false,
                    page: 1
                }
            },

            computed: {
                queryParams() {
                    let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar, { page: this.filters.page }  );

                    return this.removeJsonEmptyValues(queryParams);
                },

                queryString() {
                    return this.jsonToQueryString(this.queryParams);
                },

                mode() {
                    return this.filters?.toolbar?.mode || 'grid';
                }
            },

            watch: {
                queryParams() {
                    this.getProducts();
                },

                queryString() {
                    window.history.pushState({}, '', '?' + this.queryString);
                },
            },

            methods: {
                setFilters(type, filters) {
                    this.filters[type] = filters;
                    this.filters.page = 1
                },

                setPage(page) {
                    this.filters.page = page;
                },

                clearFilters(type, filters) {
                    this.filters[type] = {};
                    this.filters.page = 1
                },

                getProducts() {
                    this.isLoading = true;
                    this.isDrawerActive = {
                        toolbar: false,

                        filter: false,
                    };

                    document.body.style.overflow ='scroll';

                    this.$axios.get("{{ route('shop.api.products.index', $category ? ['category_id' => $category->id] : []) }}", {
                        params: this.queryParams
                    })
                        .then(response => {
                            this.isLoading = false;
                            this.products = response.data.data;
                            this.links = response.data.links;
                            this.meta = response.data.meta;
                        }).catch(error => {
                            console.error(error);
                        });
                },

                loadMoreProducts() {
                    if (! this.links.next) {
                        return;
                    }

                    this.loader = true;

                    this.$axios.get(this.links.next)
                        .then(response => {
                            this.loader = false;

                            this.products = [...this.products, ...response.data.data];

                            this.links = response.data.links;
                            this.meta = response.data.meta;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                removeJsonEmptyValues(params) {
                    Object.keys(params).forEach(function (key) {
                        if ((! params[key] && params[key] !== undefined)) {
                            delete params[key];
                        }

                        if (Array.isArray(params[key])) {
                            params[key] = params[key].join(',');
                        }
                    });
                    return params;
                },

                jsonToQueryString(params) {
                    let parameters = new URLSearchParams();

                    for (const key in params) {
                        parameters.append(key, params[key]);
                    }

                    return parameters.toString();
                }
            },
            provide() {
                // use function syntax so that we can access `this`
                return {
                    setFilters: this.setFilters,
                    clearFilters: this.clearFilters,
                }
            }
        });
    </script>
@endPushOnce
