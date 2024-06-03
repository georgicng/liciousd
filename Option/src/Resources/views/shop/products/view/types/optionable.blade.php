@if ($product->type == 'optionable')
@inject('productOptionValueRepository','Gaiproject\Option\Repositories\ProductOptionValueRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$setOptionValues = $productOptionValueRepository->getOptionValues($product);
$optionList = $productOptionValueRepository->getConfigurableOptions();
@endphp

@if ($setOptionValues && $setOptionValues->isNotEmpty())
<v-product-options :errors="errors" :option-list="{{ json_encode($optionList, 15, 512) }}" :value-list="{{ json_encode($setOptionValues, 15, 512) }}"></v-product-options>

@push('scripts')
<script type="text/x-template" id="v-product-options-template">
    <div class="w-[455px] max-w-full">

        <div v-for="option in productOptions.toSorted((a, b) => a.position - b.position)"  class="mt-[20px]">
            <h3
                class="mb-[15px] text-[20px] max-sm:text-[16px]"
                v-text="option.name"
            ></h3>

                <!-- Dropdown Options -->
                <v-field
                    as="select"
                    v-if="'select' == option.type"
                    :rules="option.rules"
                    :name="'options[' + option.id + ']'"
                    class="custom-select block w-full p-[14px] pr-[36px] bg-white border border-[#E9E9E9] rounded-lg text-[16px] text-[#6E6E6E] focus:ring-blue-500 focus:border-blue-500 max-md:border-0 max-md:outline-none max-md:w-[110px] cursor-pointer"
                    :label="option.name"
                    v-model="model[option.code]"
                >
                <option
                    v-for='(_option, index) in option.value.toSorted((a, b) => a.position - b.position)'
                    :value="_option.id"
                    :selected="_option.id == model[option.code]"
                >
                    @{{ option.nameById[_option.id] }}
                </option>
            </v-field>
            <v-field-array v-if="'multiselect' == option.type" :name="'options[' + option.id + ']'" v-model="model[option.code]" :rules="option.rules" v-slot="{ fields, replace }">
                <multiselect
                    track-by="id"
                    label="label"
                    selectLabel="Click to select"
                    deselectLabel="Click to remove"
                    :multiple="true"
                    :taggable="true"
                    :hideSelected="true"
                    :options="option.value.map(item => ({ id: item.id, label: option.nameById[item.id] }))"
                    :model-value="fields.map(entry => ({  id: entry.value, label: option.nameById[entry.value]}))"
                    class="inline-block w-auto h-10 px-1 py-2 leading-normal gray-500 border border-gray-300 rounded"
                    @update:model-value="update(option.code, $event, replace)"
                >
                </multiselect>
                <input v-for="item in model[option.code]" :name="'options[' + option.id + '][]'" type="hidden" :key="item" :value="item" >
            </v-field-array>

            <v-field
                v-if="option.type == 'text'"
                type="text"
                :name="'options[' + option.id + ']'"
                :rules="option.rules"
                v-model="model[option.code]"
                class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                :label="option.name"
            />

            <v-field
                v-if="option.type == 'textarea'"
                type="textarea"
                :name="'options[' + option.id + ']'"
                :rules="option.rules"
                v-model="model[option.code]"
                class="flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                :label="option.name"
            />

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

        props: ['errors', 'optionList', 'valueList', ],

        data() {
            return {
                model: null
            }
        },

        computed: {
            config() {
                return this.valueList.find(({
                    option_id: id
                }) => this.optionMap[id].code === 'config')
            },
            dynamic() {
                return this.config?.value?.dynamic && ['on', true].includes(this.config.value.dynamic);
            },
            rules() {
                return this.config?.value?.rules || [];
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
            optionMapByCode() {
                return this.mapToId(this.options, 'code');
            },
            productOptions() {
                return this.valueList.filter(({
                    option_id: id
                }) => id !== this.config?.option_id).map(({
                    option_id: id,
                    required,
                    value,
                    position,
                    min,
                    max
                }) => {
                    const option = this.optionMap[id];
                    const multi = ['checkbox', 'multiselect'].includes(option.type)
                    const rules = ['min', 'max', 'required'].map(key => {
                        switch (key) {
                            case 'required':
                                return required ? 'required' : '';
                            case 'min':
                                    return multi ? '' : min ? `min:${min}` : '';
                            case 'max':
                                if (multi) {
                                    return min && max ? `length:${min},${max}` : `length:${min || max}`;
                                }
                                return max ? `max:${max}` : '';
                        }
                    }).filter(item => !!item).join('|');
                    //console.log({value, rules});
                    return {
                        id,
                        rules,
                        value,
                        position,
                        ...option,
                        nameById: this.optionMap[id]?.options.reduce((acc, item) => ({
                            ...acc,
                            [item.id]: item.admin_name
                        }), {})
                    }})
            },
            valueMap() {
                return this.productOptions.reduce((acc, option) => {
                    const key = option.code
                    let value = option.value
                    if (Array.isArray(value)) {
                        value = this.mapToId(value)
                    }
                    return {
                        ...acc,
                        [key]: value
                    }
                }, {});
            },
        },

        methods: {
            transform(data) {
                return Object.keys(data).reduce((acc, key) => ({
                    ...acc,
                    [this.optionMapByCode[key].id]: data[key]
                }), {})
            },
            evalRules(rule, domain) {
                const {
                    logic,
                    conditions,
                    result
                } = rule;
                const outcome = conditions.reduce((acc, condition) => {
                    const {
                        field,
                        operator,
                        value
                    } = condition;
                    const domainValue = domain[field];
                    const isArrayCheck = Array.isArray(value);
                    let check
                    switch (operator) {
                        case 'exist':
                            check = !!domainValue
                            break;
                        case 'empty':
                            check = !domainValue
                            break;
                        case '=':
                        case 'in':
                            check = isArrayCheck ? value.includes(String(domainValue)) : domainValue == value;
                            break;
                        case '!=':
                        case 'not in':
                            check = isArrayCheck ? !value.includes(String(domainValue)) : domainValue != value;
                            break;
                        case 'regex':
                            check = domainValue.match(value);
                            break;
                        case 'include':
                            check = domainValue.every(_value => value.includes(_value));
                            break;
                        case 'exclude':
                            check = domainValue.every(_value => !value.includes(_value));
                            break;
                        case 'count':
                            check = domainValue.length == value;
                            break;
                        default:
                            check = false;
                    }
                    if (acc == null) {
                        return check
                    }
                    return logic === 'and' ? acc && check : acc || check;
                }, null);
                return outcome ? parseFloat(result) : 0;
            },
            getOptionIncrement(key, value) {
                if (!value) {
                    return 0;
                }
                if(!Array.isArray(value)){
                    value = [value];
                }
                const val = this.valueMap[key]
                return value.reduce((acc, _value) => {
                    const {
                        increment
                    } = val[_value] ? val[_value] : val;
                    return acc + increment;
                }, 0)
            },
            increment(model) {
                if (this.dynamic) {
                    return this.rules.reduce((acc, rule) => acc += this.evalRules(rule, this.transform(model)), 0)
                }
                return Object.keys(model).reduce(
                    (acc, key) => (acc += this.getOptionIncrement(key, model[key])),
                    0
                )
            },
            mapToId(col, key = 'id') {
                return col.reduce((acc, val) => ({
                    ...acc,
                    [val[key]]: val
                }), {});
            },
            update(key, valist, callback) {
                const val = valist.map(item => item.id);
                this.model[key] = val;
                if (callback && typeof callback === 'function') {
                    callback(val);
                }
                console.log(valist, val, this.model[key]);
            }
        },

        watch: {
            model: {
                handler(newVal) {
                    //console.log({ newVal})
                    this.$emitter.emit('update-price', this.increment(newVal));
                },
                deep: true
            }
        },

        created() {
            // TODO: set model from php
            this.model = this.productOptions.reduce((acc, option) => ({
                ...acc,
                [option.code]: ['multiselect', 'checkbox'].includes(option.type)? [] : ''
            }), {});
            //console.log(this.productOptions)
        },
    });
</script>
@endpush
@endif
@endif
