@if ($product->type == 'optionable')
    @inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
    @inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

    @php
        $setOptionValues = $productOptionValueRepository->getOptionValues($product);
        $optionList = $productOptionValueRepository->getConfigurableOptions();
    @endphp

    <v-product-options :errors="errors"></v-product-options>

    @push('scripts')
        <script type="text/x-template" id="v-product-options-template">
            <div class="w-[455px] max-w-full">

                <div
                    class="mt-[20px]"
                    v-for="option in productOptions"
                >
                    <!-- Dropdown Options Container -->
                    <template
                        v-if="option.type == select"
                    >
                        <!-- Dropdown Label -->
                        <h3
                            class="mb-[15px] text-[20px] max-sm:text-[16px]"
                            v-text="option.name"
                        ></h3>

                        <!-- Dropdown Options -->
                        <v-field
                            as="select"
                            :name="'options[' + option.id + ']'"
                            class="custom-select block w-full p-[14px] pr-[36px] bg-white border border-[#E9E9E9] rounded-lg text-[16px] text-[#6E6E6E] focus:ring-blue-500 focus:border-blue-500 max-md:border-0 max-md:outline-none max-md:w-[110px] cursor-pointer"
                            :label="option.name"
                            v-model="model[option.code]"
                        >
                            <option
                                v-for='(_option, index) in option.options'
                                :value="_option.id"
                                :selected="_option.id == model[option.code]"
                            >
                                @{{ _option.label }}
                            </option>
                        </v-field>
                    </template>

                    <template
                        v-if="option.type == text"
                    >
                        <!-- Textbox Label -->
                        <h3
                            class="mb-[15px] text-[20px] max-sm:text-[16px]"
                            v-text="option.name"
                        ></h3>

                        <v-field
                            type="text"
                            :name="'options[' + option.id + ']'"
                            v-model="model[option.code]"
                            class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                            :label="option.name"
                        >
                        </v-field>
                    </template>

                    <template
                        v-if="option.type == textarea"
                    >
                        <!-- Textarea Label -->
                        <h3
                            class="mb-[15px] text-[20px] max-sm:text-[16px]"
                            v-text="option.name"
                        ></h3>

                        <v-field
                            type="textarea"
                            :name="'options[' + option.id + ']'"
                            v-model="model[option.code]"
                            class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                            :label="option.name"
                        >
                        </v-field>
                    </template>

                    <v-error-message
                        :name="['options[' + option.id + ']']"
                        v-slot="{ message }"
                    >
                        <p
                            class="mt-1 text-red-500 text-xs italic"
                            v-text="message"
                        >
                        </p>
                    </v-error-message>
                </div>
            </div>
        </script>

        <script type="module">

            app.component('v-product-options', {
                template: '#v-product-options-template',

                props: ['errors'],

                data() {
                    const optionList = @json($optionList);
                    const valueList = @json($setOptionValues);
                    console.log({ optionList, valueList })

                    return {
                        optionList,
                        valueList,
                        model: {}
                    }
                },

                computed: {
                    options() {
                        if (!this.optionList?.length) {
                            return []
                        }
                        return this.optionList.map(
                            ({
                                id,
                                code,
                                name,
                                values,
                                type
                            }) => {
                                return {
                                    id,
                                    code,
                                    name,
                                    type,
                                    options: values,
                                }
                            }
                        );
                    },
                    optionMap() {
                        return this.mapToId(this.options);
                    },
                    optionValues() {
                        return this.options.flatMap(item => item.options)
                    },
                    optionValueMap() {
                        return this.mapToId(this.optionValues);
                    },
                    productOptions() {
                        if (!this.valueList?.length) {
                            return []
                        }

                        return this.valueList.filter(({
                            option_id: id
                        }) => !this.optionMap[id].is_sys_defined).map(({
                            option_id: id,
                            required,
                            value
                        }) => ({
                            id,
                            required,
                            value,
                            ...this.optionMap[id]
                        }))
                    },
                    valueMap() {
                        if (!this.valueList?.length) {
                            return {}
                        }
                        return this.mapToId(this.valueList, 'option_id');
                    },
                    optionListMap() {
                        return this.mapToId(this.optionList);
                    },
                },

                methods: {
                    mapToId(col, key = 'id') {
                        return col.reduce((acc, val) => ({
                            ...acc,
                            [val[key]]: val
                        }), {});
                    },

                    increment(model) {
                        // model is key value, use model key and value to get increment and prefix based on productionoption value map
                        return Object.keys(model).reduce((acc, key) => {
                            const increment = 0 //getOptionIncrement(key, model[key], incrementMap, OPTION_KEY_MAP)
                            return (acc += increment)
                        }, 0)
                    }
                },

                watch: {
                    model(newVal) {
                        this.$emitter.emit('update-price', this.increment(newVal));
                    }
                },

                created() {
                    this.productOptions.forEach(option => {
                        this.model[option.code] = '';
                    });
                },

                mounted() {
                    console.log({ productOptions: this.productOptions, optionMap: this.optionMap })
                }
            });

        </script>
    @endpush
@endif