
@props(['product' => null])
{!! view_render_event('bagisto.shop.products.view.bundle-options.before', ['product' => $product]) !!}

<v-product-bundle-options {{ $attributes }}></v-product-bundle-options>

{!! view_render_event('bagisto.shop.products.view.bundle-options.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-bundle-options-template"
    >
        <div class="mt-8">
            <template v-for="(option, index) in options">
                <x-licious::products.types.bundle.item
                    ::option="option"
                    ::errors="errors"
                    ::key="index"
                    @onProductSelected="productSelected(option, $event)"
                />
            </template>

            <div class="flex justify-between items-center my-[20px]">
                <p class="text-sm">
                    @lang('Total Amount')
                </p>

                <p class="text-lg font-medium">
                    @{{ formattedTotalPrice }}
                </p>
            </div>

            <ul class="grid gap-2.5 text-base">
                <li v-for="option in options">
                    <span class="inline-block mb-1.5">
                        @{{ option.label }}
                    </span>

                    <template v-for="product in option.products">
                        <div
                            class="text-[#6E6E6E]"
                            :key="product.id"
                            v-if="product.is_default"
                        >
                            @{{ product.qty + ' x ' + product.name }}
                        </div>
                    </template>
                </li>
            </ul>
        </div>
    </script>

    <script type="module">
        app.component('v-product-bundle-options', {
            template: '#v-product-bundle-options-template',

            props: ['errors'],

            data: function() {
                return {
                    config: @json(app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product)),

                    options: [],

                }
            },

            computed: {
                formattedTotalPrice: function() {
                    var total = 0;

                    for (var key in this.options) {
                        for (var key1 in this.options[key].products) {
                            if (! this.options[key].products[key1].is_default)
                                continue;

                            total += this.options[key].products[key1].qty * this.options[key].products[key1].price.final.price;
                        }
                    }

                    return this.$shop.formatPrice(total);
                }
            },

            created: function() {
                for (var key in this.config.options) {
                    this.options.push(this.config.options[key]);
                }
            },

            methods: {
                productSelected: function(option, value) {
                    var selectedProductIds = Array.isArray(value) ? value : [value];

                    for (var key in option.products) {
                        option.products[key].is_default = selectedProductIds.indexOf(option.products[key].id) > -1 ? 1 : 0;
                    }
                }
            }
        });
    </script>
@endPushOnce
