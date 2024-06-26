@inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$setOptions = $productOptionValueRepository->getFamilyOptions($product);
$setOptionValues = $productOptionValueRepository->getOptionValues($product);
$optionList = $productOptionValueRepository->getConfigurableOptions();
@endphp
{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.optionable.before', ['product' => $product]) !!}

<v-product-options :product-id="{{ $product->id }}" :errors="errors" :set-options="{{ json_encode($setOptions, 15, 512) }}" :option-list="{{ json_encode($optionList, 15, 512) }}" :value-list="{{ json_encode($setOptionValues, 15, 512) }}"></v-product-options>

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
                <draggable
                    tag="ul"
                    ghost-class="draggable-ghost"
                    class="flex-none w-32 flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0"
                    v-bind="{animation: 200}"
                    v-model="sort"
                    item-key="option_id"
                >
                    <template #item="{ element, index }">
                        <li>
                            <button
                                type="button"
                                class="py-2 px-3 w-full flex items-center focus:outline-none focus-visible:underline"
                                :class="{ 'bg-gray-50 dark:bg-gray-800':  element.option_id === selectedOption.id }"
                                @click="select(element.option_id)"
                            >
                                <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i><span>@{{optionMap[element.option_id].name}}</span>
                            </button>
                        </li>
                    </template>
                    <template #footer>
                        <li>
                            <v-autocomplete :items="availableOptions" @add="addOption($event)"/>
                        </li>
                    </template>

                </draggable>

                <div class="flex-1 p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg">
                    <template v-if="model.length">
                        <v-product-option-item
                            v-for="(option, index) in model"
                            v-show="selectedOption.id === option.option_id"
                            :option="optionListMap[option.option_id]"
                            :value="modelMap[option.option_id]"
                            :index="index"
                            :dynamic-pricing="dynamic"
                            :errors="errors"
                            :key="option.option_id"
                            @updateValue="updateOption(index, $event)"
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
                        v-model="model.required"
                    >
                        <option value="0" >
                            No
                        </option>
                        <option value="1" >
                            Yes
                        </option>
                    </v-field>

                    <v-error-message
                        :name="`options[${index}][required]`"
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
                        :value="value['value']"
                        :type="option.type"
                        :dynamic-pricing="dynamicPricing"
                        @updateValue="update('value', $event)"
                    />
                    <v-product-option-select
                        v-else-if="['select', 'checkbox', 'multiselect'].includes(option.type)"
                        :control-name="`options[${index}][value]`"
                        :value="value['value']"
                        :options="option.values"
                        :dynamic-pricing="dynamicPricing"
                        @updateValue="update('value', $event)"
                    />

                    <v-error-message
                        :name="`options[${index}][value]`"
                        v-slot="{ message }"
                    >
                        <p
                            class="mt-1 text-red-600 text-xs italic"
                            v-text="message"
                        >
                        </p>
                    </v-error-message>
                </x-admin::form.control-group>
                <template v-if="['text', 'textarea', 'checkbox', 'multiselect'].includes(option.type)">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="min">
                            Min
                        </x-admin::form.control-group.label>

                        <v-field
                            :name="`options[${index}][min]`"
                            type="text"
                            v-model="model.min"
                        />

                        <v-error-message
                            :name="`options[${index}][min]`"
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
                        <x-admin::form.control-group.label class="max">
                            Max
                        </x-admin::form.control-group.label>

                        <v-field
                            :name="`options[${index}][max]`"
                            type="text"
                            v-model="model.max"
                        />

                        <v-error-message
                            :name="`options[${index}][max]`"
                            v-slot="{ message }"
                        >
                            <p
                                class="mt-1 text-red-600 text-xs italic"
                                v-text="message"
                            >
                            </p>
                        </v-error-message>
                    </x-admin::form.control-group>
                </template>
                <input v-if="value.id" type="hidden" :name="`options[${index}][id]`" :value="model.id" />
                <input type="hidden" :name="`options[${index}][option_id]`" :value="model.option_id" />
                <input type="hidden" :name="`options[${index}][product_id]`" :value="model.product_id" />
                <input type="hidden" :name="`options[${index}][position]`" :value="index" />
            </div>
        </div>
    </script>

{{-- Variation Item Template --}}
<script type="text/x-template" id="v-product-option-input-template">
    <div>
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @{{ type == 'boolean' ? 'Label' : 'Default Value' }}
                </x-admin::form.control-group.label>
                <v-field
                    :type="type"
                    :name="`${controlName}[${type == 'boolean' ? 'label' : 'default'}]`"
                    v-model="model.default"
                />
            </x-admin::form.control-group>
            <x-admin::form.control-group v-show="!dynamicPricing">
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
                        <th scope="col" v-show="!dynamicPricing">Price</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <draggable
                    tag="tbody"
                    ghost-class="draggable-ghost"
                    v-bind="{animation: 200}"
                    :list="model"
                    item-key="id"
                >
                    <template #item="{ element: value, index }">
                        <tr>
                            <th scope="row">
                                <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i> @{{ nameById[value.id] }}
                                <input type="hidden" :name="`${controlName}[${index}][id]`" :value="value.id" />
                                <input type="hidden" :name="`${controlName}[${index}][position]`" :value="index" />
                            </th>
                            <td v-show="!dynamicPricing">
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

                    <template v-if="!model.length" #header>
                        <tr><td colspan="3">Please add an option item to begin</td></tr>
                    </template>
                </draggable>
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
                        <td><button type="button" @click="add">add</button> <button v-if="unassignedOptions.length > 1" type="button" @click="addAll">add all</button></td>
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

        props: ['errors', 'optionList', 'valueList', 'setOptions', 'productId'],

        data() {
            return {
                model: [],
                selectedOption: {},
                dynamic: false
            }
        },

        computed: {
            sort: {
                get() {
                    return this.model.map(({
                        option_id,
                        position
                    }) => ({
                        option_id,
                        position
                    })).sort((a, b) => a.position - b.position)
                },
                set(val) {
                    this.model = this.model.map(item => {
                        const index = val.findIndex(_item => _item.option_id == item.option_id)
                        return {
                            ...item,
                            position: index
                        }
                    })
                }
            },
            config() {
                return this.valueList.find(({
                    option_id: id
                }) => this.optionMap[id].code === 'config')
            },
            configIndex() {
                return this.valueList.length + 1;
            },
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
            valueMap() {
                if (!this.valueList?.length) {
                    return {}
                }
                return this.mapToId(this.valueList, 'option_id');
            },
            modelMap() {
                if (!this.model?.length) {
                    return {}
                }
                return this.mapToId(this.model, 'option_id');
            },
            optionListMap() {
                return this.mapToId(this.optionList);
            },
            availableOptions() {
                return this.optionList.filter(
                    item => item.code !== 'config' && !this.model.find(_item => _item.id === item.option_id)
                )
            },
        },
        methods: {
            addOption(id) {
                const option = this.optionListMap[id];
                this.model.push({
                    required: 0,
                    value: ['select', 'multiselect', 'checkbox'].includes(option.type) ? [] : {},
                    product_id: this.productId,
                    option_id: id,
                    min: '',
                    max: ''
                });
                this.select(id);
            },
            updateOption(index, value) {
                this.model[index] = value
            },
            removeOption(id) {
                this.model = this.model.filter(item => item.option_id !== id);
                if (this.selectedOption.id === id) {
                    this.model.length && this.select(this.modelt[0].option_id);
                }
            },
            select(id) {
                this.selectedOption = this.optionMap[id];
            },
            mapToId(col, key = 'id') {
                return col.reduce((acc, val) => ({
                    ...acc,
                    [val[key]]: {
                        ...val
                    }
                }), {});
            },
            togglePricing() {
                this.dynamic = !this.dynamic;
            },
        },
        created() {
            this.selectedOption = this.options[0];
            this.dynamic = this.config?.value?.dynamic && ['on', true].includes(this.config.value.dynamic);
            this.model = [...this.valueList.filter(item => item.option_id != this.config?.option_id)].sort((a, b) => a.position - b.position);
        },
    });


    app.component('v-product-option-item', {
        template: '#v-product-option-item-template',
        props: [
            'option',
            'value',
            'index',
            'dynamicPricing'
        ],
        data() {
            return {
                model: this.value
            }
        },
        methods: {
            update(key, value) {
                this.model[key] = value
                this.$emit('updateValue', this.model)
            }
        },
        watch: {
            value(newVal) {
                this.model = newVal
            }
        }
    });

    app.component('v-product-option-select', {
        template: '#v-product-option-select-template',

        props: [
            'options',
            'value',
            'controlName',
            'dynamicPricing'
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
                this.$emit('updateValue', this.model)
            },
            addAll() {
                const append = this.unassignedOptions.map(item => ({
                    id: item.id,
                    prefix: '+',
                    price: ''
                }))
                this.model = [this.model, append]
                this.$emit('updateValue', this.model)
            },
            remove(id) {
                this.model = this.model.filter(item => item.id != id)
                this.$emit('updateValue', this.model)
            },
            edit({
                key,
                value,
                id
            }) {
                const index = this.model.findIndex(item => item.id == id)
                this.model[index][key] = value;
                this.$emit('updateValue', this.model)
            }
        },
        watch: {
            value(newVal, oldVal) {
                this.model = newVal
            }
        }

    });

    app.component('v-product-option-input', {
        template: '#v-product-option-input-template',

        props: [
            'value',
            'controlName',
            'option',
            'dynamicPricing'
        ],

        data() {
            return {
                model: !this.isEmpty(this.value) ? this.value : {
                    default: '',
                    prefix: '+',
                    price: '',
                }
            }
        },
        methods: {
            isEmpty(value) {
                return !value || !Object.keys(value).length
            }
        },
        watch: {
            model(newVal) {
                this.$emit('updateValue', newVal)
            },
            value(newVal) {
                this.model = newVal
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
