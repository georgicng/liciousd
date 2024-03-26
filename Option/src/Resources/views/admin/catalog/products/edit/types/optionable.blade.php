@inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$setOptions = $productOptionValueRepository->getFamilyOptions($product);
$allOptions = [];
if (!empty($setOptions)) {
    $allOptions = $productOptionValueRepository->getConfigurableOptions();
}
@endphp
{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.optionable.before', ['product' => $product]) !!}

<v-product-options :errors="errors"></v-product-options>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.optionable.after', ['product' => $product]) !!}

@pushOnce('scripts')
{{-- Variations Template --}}
<script type="text/x-template" id="v-product-options-template">
    <div class="relative bg-white dark:bg-gray-900  rounded-[4px] box-shadow">
            <!-- Panel Header -->
            <div class="flex flex-wrap gap-[10px] justify-between mb-[10px] p-[16px]">
                <div class="flex flex-col gap-[8px]">
                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                        @lang('option::app.admin.catalog.products.edit.types.optionable.title')
                    </p>

                    <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                        @lang('option::app.admin.catalog.products.edit.types.optionable.info')
                    </p>
                </div>

                <!-- Add Button -->
                <div class="flex gap-x-[4px] items-center">
                    <div
                        class="secondary-button"
                        @click="$refs.variantCreateModal.open()"
                    >
                        @lang('option::app.admin.catalog.products.edit.types.optionable.add-btn')
                    </div>
                </div>
            </div>

            <template v-if="options.length">

                <!-- Panel Content -->
                <div class="grid">
                    <v-product-option-item
                        v-for='(option, index) in options'
                        :key="index"
                        :index="index"
                        :variant="option"
                        @onRemoved="removeOption"
                        :errors="errors"
                    ></v-product-option-item>
                </div>
            </template>

            <!-- For Empty Variations -->
            <template v-else>
                <div class="grid gap-[14px] justify-center justify-items-center py-[40px] px-[10px]">
                    <!-- Placeholder Image -->
                    <img
                        src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                        class="w-[80px] h-[80px] dark:invert dark:mix-blend-exclusion"
                    />

                    <!-- Add Variants Information -->
                    <div class="flex flex-col items-center">
                        <p class="text-[16px] text-gray-400 font-semibold">
                            @lang('option::app.admin.catalog.products.edit.types.optionable.empty-title')
                        </p>

                        <p class="text-gray-400">
                            @lang('option::app.admin.catalog.products.edit.types.optionable.empty-info')
                        </p>
                    </div>

                    <!-- Add Row Button -->
                    <div
                        class="secondary-button text-[14px]"
                        @click="$refs.optionCreateModal.open()"
                    >
                        @lang('option::app.admin.catalog.products.edit.types.optionable.add-btn')
                    </div>
                </div>
            </template>
        </div>
    </script>


{{-- Variation Item Template --}}
<script type="text/x-template" id="v-product-option-item-template">
    <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800">
        </div>
    </script>

<script type="module">
    app.component('v-product-options', {
        template: '#v-product-options-template',

        props: ['errors'],

        data() {
            const options = @json($allOptions);
            const setOptions = @json($setOptions);
            const selectedOption = {};
            console.log({
                options,
                setOptions
            })
            return {
                options,
                setOptions,
                selectedOption,
            }
        },

        methods: {
            addOption(params, {
                resetForm
            }) {
                let self = this;

                let filteredVariants = this.variants.filter(function(variant) {
                    let matchCount = 0;

                    for (let key in params) {
                        if (variant[key] == params[key]) {
                            matchCount++;
                        }
                    }

                    return matchCount == self.superAttributes.length;
                })

                if (filteredVariants.length) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: "@lang('option::app.admin.catalog.products.edit.types.optionable.create.variant-already-exists')"
                    });

                    return;
                }

                const optionIds = Object.values(params);

                this.variants.push(Object.assign({}, params));

                resetForm();

                this.$refs.variantCreateModal.close();
            },

            removeOption(option) {
                this.$emitter.emit('open-confirm-modal', {
                    agree: () => {
                        this.options.splice(this.options.indexOf(option), 1);
                    },
                });
            },
        }
    });


    app.component('v-product-option-item', {
        template: '#v-product-option-item-template',

        props: [
            'option',
        ],

        data() {
            return {}
        },

        created() {},

        mounted() {},

        computed: {},

        watch: {},

        methods: {}
    });
</script>
@endPushOnce
