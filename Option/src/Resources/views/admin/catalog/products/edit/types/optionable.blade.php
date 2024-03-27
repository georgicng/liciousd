@inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$setOptions = $productOptionValueRepository->getFamilyOptions($product);
$setOptionValues = $productOptionValueRepository->getOptionValues($product);
$optionList = [];
if (!empty($setOptions)) {
$optionList = $productOptionValueRepository->getConfigurableOptions();
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
            <div class="flex flex-row">
                <ul
                    class="flex-none w-32 flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0"
                >
                    <li
                        v-for="option in options"
                    >
                        <button
                            type="button"
                            class="py-2 px-3 w-full flex items-center focus:outline-none focus-visible:underline"
                            :class="{ 'bg-gray-50 dark:bg-gray-800':  option.id === selectedOption.id }"
                            @click="select(option.id)"
                        >
                            <span>@{{option.name}}</span>
                        </button>
                    </li>
                </ul>

                <div class="flex-1 p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg">
                    <v-product-option-item
                        :option="selectedOption"
                        :value="valueMap[selectedOption.id]"
                        :errors="errors"
                    ></v-product-option-item>
                </div>
            </div>
        </template>
    </div>
</script>


{{-- Variation Item Template --}}
<script type="text/x-template" id="v-product-option-item-template">
    <div class="animate-[on-fade_0.5s_ease-in-out]">

        <div class="flex-column gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800">
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    Required
                </x-admin::form.control-group.label>

                <v-field
                    as="select"
                    :name="'options[' + option.id + '][required]'"
                    class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                    :value="value['required']"
                >
                    <option value="0" >
                        No
                    </option>
                    <option value="1" >
                        Yes
                    </option>
                </v-field>

                <v-error-message
                    :name="required"
                    v-slot="{ message }"
                >
                    <p
                        class="mt-1 text-red-600 text-xs italic"
                        v-text="message"
                    >
                    </p>
                </v-error-message>
            </x-admin::form.control-group>
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">
                    Value
                </x-admin::form.control-group.label>
                <v-field
                    v-if="option.type == 'text'"
                    type="text"
                    :name="'options[' + option.id + '][value]'"
                    :value="value['value']"
                />
                <v-option-select-item
                    v-if="option.type == 'select'"
                    :name="'options[' + option.id + '][value]'"
                    :value="value['value']"
                />

                <v-error-message
                    :name="value"
                    v-slot="{ message }"
                >
                    <p
                        class="mt-1 text-red-600 text-xs italic"
                        v-text="message"
                    >
                    </p>
                </v-error-message>
            </x-admin::form.control-group>
        </div>
    </div>
</script>

{{-- Option Pricing Template --}}
<script type="text/x-template" id="v-option-select-item-template">
    <div>
        <table>
            <thead>
                <tr>
                    <th scope="col">Option Value</th>
                    <th scope="col">Price</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in values" :key="value.id">
                    <th scope="row">
                        @{{ nameById[value.id] }}
                    </th>
                    <td>
                        <v-field
                            as="select"
                            name="prefix"
                            @select="edit({ key: 'prefix', value: $event, id: value.id)"
                        >
                            <option value="+" >
                                +
                            </option>
                            <option value="-" >
                                -
                            </option>
                        </v-field>
                        <v-field
                            type="text"
                            name="price"
                            :value="item.price"
                            @input="edit({ key: 'price', value: $event, id: value.id)"
                        />
                    </td>
                    <td><button @click="remove(value.id)">remove</button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="2">
                        <v-field
                            as="select"
                            v-model="option"
                            class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                            label="required"
                        >
                            <option v-for="option in unassignedOptions" :key="option.id" :value="option.id" >
                                @{{option.admin_name}}
                            </option>
                        </v-field>
                    </th>
                    <td><button @click="add">add</button></td>
                </tr>
            </tfoot>
        </table>
    </div>
</script>


<script type="module">
    app.component('v-product-options', {
        template: '#v-product-options-template',

        props: ['errors'],

        data() {
            const options = @json($optionList);
            const setOptions = @json($setOptions);
            const valueList = @json($setOptionValues);
            const selectedOption = {};
            return {
                options,
                setOptions,
                selectedOption,
            }
        },

        computed: {
            valueMap() {
                if (!this.valueList?.length) {
                    return {}
                }
                return this.valueList.reduce((acc, val) => ({
                    ...acc,
                    [val.option_id]: val
                }), {});
            },
            options() {
                if (!this.setOptions?.length) {
                    return []
                }
                return this.setOptions.flatMap(item => {
                    return item.custom_options.map(({
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
                    })

                });
            },
            optionMap() {
                return this.options.reduce((acc, val) => ({
                    ...acc,
                    [val.id]: val
                }), {});
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
            select(id) {
                this.selectedOption = this.optionMap[id];
            }
        },
        created() {
            this.selectedOption = this.options[0];
        }
    });


    app.component('v-product-option-item', {
        template: '#v-product-option-item-template',

        props: [
            'option',
            'value'
        ],

        data() {
            return {}
        },

    });

    app.component('v-option-select-item', {
        template: '#v-option-select-item-template',

        props: [
            'options',
            'value'
        ],
        computed: {
            assignedOptions() {
                return this.model.map(item => item.id)
            },
            unassignedOptions() {
                return this.options.filter(item => !this.assignedOptions.includes(item.id))
            },
            nameById() {
                return this.options.reduce((acc, item) => ({ ...acc, [item.id]: item.admin_name }), {})
            }
        },

        data() {
            return {
                option: '',
                model: this.value || []
            }
        },
        methods: {
            add() {
                this.model.push({ id: '', prefix: '+', price: ''});
                this.option = '';
            },
            remove(id) {
                this.model = this.model.filter(item => item.id != id)
            },
            edit({ key, value, id}) {
                const index = this.model.findIndex(item => item.id == id)
                this.model.splice(index, 1, { ...this.model[index], [key]: value })
            }
        }

    });
</script>
@endPushOnce
