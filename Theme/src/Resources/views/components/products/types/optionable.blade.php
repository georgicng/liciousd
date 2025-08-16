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
        <div v-for="option in productOptions.toSorted((a, b) => a.position - b.position)"  class="pt-[20px]">
            <h3
                v-if="['text', 'textarea'].includes(option.type)"
                class="mb-[15px] text-[20px] max-sm:text-[16px]"
                v-text="option.name"
            ></h3>

            <!-- Dropdown Options -->
            <Tags
                v-if="'select' == option.type"
                :rules="option.rules"
                :label="option.name"
                :name="'options[' + option.id + ']'"
                :options="option.value.map(item => ({ id: item.id, name: option.nameById[item.id] }))"
                v-model="model[option.code]"
            />
            <Tags
                v-if="'multiselect' == option.type"
                :label="option.name"
                :rules="option.rules"
                :name="'options[' + option.id + '][]'"
                :multiple="true"
                :options="option.value.map(item => ({ id: item.id, name: option.nameById[item.id] }))"
                v-model="model[option.code]"
            />
            <v-field
                v-if="option.type == 'text'"
                type="text"
                :name="'options[' + option.id + ']'"
                :rules="option.rules"
                v-model="model[option.code]"
                class="w-full h-[50px] py-[5px] px-[20px] outline-[0] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] twxt-[#777] text-[14px]"
                :label="option.name"
            />

            <v-field
                v-if="option.type == 'textarea'"
                type="textarea"
                :name="'options[' + option.id + ']'"
                :rules="option.rules"
                v-model="model[option.code]"
                class="w-full h-[150px] mb-[15px] p-[20px] bg-transparent text-[14px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-[#777] outline-[0]"
                :label="option.name"
            />

            <v-error-message
                :name="`options[${option.id}]${'multiselect' == option.type ? '[]' : ''}`"
                v-slot="{ message }"
            >
                <p
                    class="mt-1 text-red-500 text-xs italic"
                    v-text="message"
                >
                </p>
            </v-error-message>
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
                    const rules = [...(multi && min != '' && max != ''? ['minmax'] : ['min', 'max']), 'required'].map(key => {
                        switch (key) {
                            case 'required':
                                return required == '1' ? 'required' : '';
                            case 'min':
                                    return min != '' ? `min:${min}` : '';
                            case 'max':
                                return max != '' ? `max:${max}` : '';
                            case 'minmax':
                                return `minMax:${min},${max}`;
                        }
                    }).filter(item => !!item).join('|');
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
            console.log(this.productOptions)
        },
    });
</script>
@endpush
@endif
