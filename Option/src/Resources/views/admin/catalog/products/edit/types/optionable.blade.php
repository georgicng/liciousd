@inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$setOptions = $productOptionValueRepository->getFamilyOptions($product);
$setOptionValues = $productOptionValueRepository->getOptionValues($product);
$optionList = $productOptionValueRepository->getConfigurableOptions();
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
        </div>


        <div class="flex flex-row">
            <ul
                class="flex-none w-32 flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0"
            >
                <template v-if="productOptions.length">
                    <li
                        v-for="option in productOptions"
                    >
                        <button
                            type="button"
                            class="py-2 px-3 w-full flex items-center focus:outline-none focus-visible:underline"
                            :class="{ 'bg-gray-50 dark:bg-gray-800':  option.id === selectedOption.id }"
                            @click="select(option.id)"
                        >
                            <span>@{{optionMap[option.id].name}}</span>
                        </button>
                    </li>
                </template>
                <li>
                    <v-autocomplete :items="availableOptions" @add="addOption($event)"/>
                </li>
            </ul>

            <div class="flex-1 p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg">
                <template v-if="productOptions.length">
                    <v-product-option-item
                        v-for="(option, index) in productOptions"
                        v-show="selectedOption.id === option.id"
                        :option="optionListMap[option.id]"
                        :value="valueMap[option.id]"
                        :index="index"
                        :errors="errors"
                    ></v-product-option-item>
                </template>
            </div>
        </div>
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
                    :name="`options[${index}][required]`"
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
                <v-product-option-input
                    v-if="['text', 'textarea'].includes(option.type)"
                    :control-name="`options[${index}][value]`"
                    :option="value['value']"
                />
                <v-product-option-select
                    v-if="option.type == 'select'"
                    :control-name="`options[${index}][value]`"
                    :value="value['value']"
                    :options="option.values"
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
            <input v-if="value.id" type="hidden" :name="`options[${index}][id]`" :value="value.id" />
            <input type="hidden" :name="`options[${index}][option_id]`" :value="value.option_id" />
            <input type="hidden" :name="`options[${index}][product_id]`" :value="value.product_id" />
        </div>
    </div>
</script>

{{-- Variation Item Template --}}
<script type="text/x-template" id="v-product-option-input-template">
    <div>
        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                Default Value
            </x-admin::form.control-group.label>
            <v-field
                :type="option.type"
                :name="`${controlName}[default]`"
                v-model="model.default"
            />
        </x-admin::form.control-group>
        <x-admin::form.control-group>
            <x-admin::form.control-group.label>
                Price
            </x-admin::form.control-group.label>
            <v-field
                as="select"
                :name="`${controlName}[prefix]`"
                v-model="model.prefix"
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
                :name="`${controlName}[price]`"
                v-model="model.price"
            />
        </x-admin::form.control-group>
    </div>
</script>

{{-- Option Pricing Template --}}
<script type="text/x-template" id="v-product-option-select-template">
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
                <template v-if="model.length">
                    <tr v-for="(value, index) in model" :key="value.id">
                        <th scope="row">
                            @{{ nameById[value.id] }}
                            <input type="hidden" :name="`${controlName}[${index}][id]`" :value="value.id" />
                        </th>
                        <td>
                            <select
                                as="select"
                                :name="`${controlName}[${index}][prefix]`"
                                :value="value.prefix"
                                @change="edit({ key: 'prefix', value: $event.target.value, id: value.id })"
                            >
                                <option value="+" >
                                    +
                                </option>
                                <option value="-" >
                                    -
                                </option>
                            </select>
                            <input
                                type="text"
                                :name="`${controlName}[${index}][price]`"
                                :value="value.price"
                                @input="edit({ key: 'price', value: $event.target.value, id: value.id })"
                            />
                        </td>
                        <td><button type="button" @click="remove(value.id)">remove</button></td>
                    </tr>
                </template>
                <template v-else>
                    <tr><td colspan="3">Please add an option item to begin</td></tr>
                </template>
            </tbody>
            <tfoot v-if="unassignedOptions.length">
                <tr>
                    <th scope="row" colspan="2">
                        <select
                            as="select"
                            v-model="option"
                            class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                            label="required"
                        >
                            <option v-for="item in unassignedOptions" :key="item.id" :value="item.id" >
                                @{{item.admin_name}}
                            </option>
                        </select>
                    </th>
                    <td><button type="button" @click="add">add</button></td>
                </tr>
            </tfoot>
        </table>
    </div>
</script>

{{-- Autocomplete --}}
<script type="text/x-template" id="v-autocomplete-template">
    <div v-if="items.length" class="relative w-[130px]">
        <input type="text" v-model="search" @keyup.down="onArrowDown" @keyup.up="onArrowUp" @keyup.enter="onEnter" />
        <ul v-if="candidates.length"  id="autocomplete-results" class="p-0 m-0 border-[1px] border-[solid] border-[#eeeeee] h-[120px] overflow-auto w-full">
            <li v-for="(item, i) in candidates" :key="item.id" class="[list-style:none] text-left px-[2px] py-[4px] cursor-pointer" :class="{ 'hover:bg-[#4aae9b] hover:text-[white]': i === index }" @click="selected(item.id)">
                @{{ item.admin_name }}
            </li>
        </ul>
    </div>
</script>


<script type="module">
    app.component('v-product-options', {
        template: '#v-product-options-template',

        props: ['errors'],

        data() {
            const optionList = @json($optionList);
            const setOptions = @json($setOptions);
            const valueList = @json($setOptionValues);
            const selectedOption = {};
            return {
                optionList,
                setOptions,
                valueList,
                selectedOption,
            }
        },

        computed: {
            options() {
                if (!this.optionList?.length) {
                    return []
                }
                return this.optionList.map(({
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
                    });
            },
            optionMap() {
                return this.mapToId(this.options);
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
                    value
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
            availableOptions() {
                return this.optionList.filter(
                    item => item.code !== 'config' && !this.productOptions.find(_item => _item.id === item.id)
                )
            },
        },

        methods: {
            addOption(id) {
                const option = this.optionListMap[id];
                this.valueList.push({
                    required: 0,
                    value: ['select'].includes(option.type) ? [] : {},
                    product_id: {{ $product->id }},
                    option_id: id
                });
                this.select(id);
            },

            removeOption(id) {
                this.valueList = this.valueList.filter(item => item.option_id !== id);
                if (this.selectedOption.id === id) {
                    this.valueList.length && this.select(this.valueList[0].option_id);
                }
            },
            select(id) {
                this.selectedOption = this.optionMap[id];
            },
            mapToId(col, key = 'id') {
                return col.reduce((acc, val) => ({
                    ...acc,
                    [val[key]]: val
                }), {});
            },
        },
        created() {
            this.selectedOption = this.options[0];
        }
    });


    app.component('v-product-option-item', {
        template: '#v-product-option-item-template',

        props: [
            'option',
            'value',
            'index'
        ],

        data() {
            return {}
        },

    });

    app.component('v-product-option-select', {
        template: '#v-product-option-select-template',

        props: [
            'options',
            'value',
            'controlName',
        ],
        computed: {
            assignedOptions() {
                return this.model.map(item => String(item.id))
            },
            unassignedOptions() {
                return this.options.filter(item => !this.assignedOptions.includes(String(item.id)))
            },
            nameById() {
                return this.options.reduce((acc, item) => ({
                    ...acc,
                    [item.id]: item.admin_name
                }), {})
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
                this.model.push({
                    id: this.option,
                    prefix: '+',
                    price: ''
                });
                this.option = '';
            },
            remove(id) {
                this.model = this.model.filter(item => item.id != id)
            },
            edit({
                key,
                value,
                id
            }) {
                const index = this.model.findIndex(item => item.id == id)
                this.model[index][key] = value;
            }
        }

    });

    app.component('v-product-option-input', {
        template: '#v-product-option-input-template',

        props: [
            'option',
            'controlName'
        ],

        data() {
            return {
                model: Object.keys(this.option).length ? this.option : {
                    default: '',
                    prefix: '+',
                    price: '',
                }
            }
        },
    });

    app.component('v-autocomplete', {
        template: "#v-autocomplete-template",
        props: {
            items: {
                type: Array,
                required: true,
            },
        },

        data() {
            return {
                search: "",
                index: 0
            };
        },
        computed: {
            candidates() {
                const search = this.search;

                if (!search) {
                    return [];
                }

                return this.items.filter(item => item.admin_name.toLowerCase().indexOf(search.toLowerCase()) > -1);
            },
        },
        methods: {
            selected(id) {
                this.$emit("add", id);
                this.search = '';
            },
            onArrowDown() {
                if (this.index < this.candidates?.length) {
                    this.index += 1;
                }
            },
            onArrowUp() {
                if (this.arrowCounter > 0) {
                    this.index -= 1;
                }
            },
            onEnter() {
                this.selected(this.candidates[this.index]?.id);
                this.resetIndex();
            },
            resetIndex() {
                this.index = -1;
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.resetIndex();
                }
            }
        },
        watch: {
            search: function() {
                if (this.index !== -1) {
                    this.resetIndex();
                }
            }
        },
        mounted() {
            document.addEventListener("click", this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener("click", this.handleClickOutside);
        }
    });
</script>
@endPushOnce
