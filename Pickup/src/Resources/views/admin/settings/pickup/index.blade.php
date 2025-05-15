@php
    $countryCode = config('app.default_country');
    $countries = core()->countries();
    $setCountry = $countries->firstWhere('code', $countryCode);

    $stateCode = config('app.default_state');
    $states = core()->states($countryCode);
    $setState = $stateCode ? $states->firstWhere('code', $stateCode) : $states->first();
@endphp
<x-admin::layouts>
    <x-slot:title>
        @lang('pickup::app.admin.settings.pickup.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.pickup.create.before') !!}

    <v-centres>
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('pickup::app.admin.settings.pickup.index.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Craete currency Button -->
                @if (bouncer()->hasPermission('settings.pickup.create'))
                <button type="button" class="primary-button">
                    @lang('pickup::app.admin.settings.pickup.index.create-btn')
                </button>
                @endif
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid />
    </v-centres>

    {!! view_render_event('bagisto.admin.settings.pickup.create.after') !!}

    @pushOnce('scripts')
    <script type="text/x-template" id="v-centres-template">
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('pickup::app.admin.settings.pickup.index.title')
                </p>

                <div class="flex gap-x-2.5 items-center">
                    <!-- Craete currency Button -->
                    @if (bouncer()->hasPermission('settings.pickup.create'))
                    <!-- Filters Activation Button -->
                        <x-admin::drawer width="500px" ref="centreUpdateOrCreateDrawer">
                            <x-slot:toggle>
                                <button
                                    type="button"
                                    class="primary-button"
                                    @click="id=0; selectedCentre={};"
                                >
                                    @lang('pickup::app.admin.settings.pickup.index.create-btn')
                                </button>
                            </x-slot>

                            <!-- Drawer Header -->
                            <x-slot:header>
                                <div class="flex justify-between items-center p-3">
                                    <p
                                        class="text-base text-gray-800 dark:text-white font-bold"
                                        v-if="id"
                                    >
                                        @lang('pickup::app.admin.settings.pickup.index.edit.title')
                                    </p>

                                    <p
                                        class="text-base text-gray-800 dark:text-white font-bold"
                                        v-else
                                    >
                                        @lang('pickup::app.admin.settings.pickup.index.create.title')
                                    </p>
                                </div>
                            </x-slot>

                            <!-- Drawer Content -->
                            <x-slot:content class="!p-5">
                                <x-admin::form
                                    v-slot="{ meta, errors, handleSubmit }"
                                    as="div"
                                >
                                    <form
                                        @submit="handleSubmit($event, updateOrCreate)"
                                        ref="pickupCreateForm"
                                    >
                                        <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                            {!! view_render_event('bagisto.admin.settings.pickup.create.before') !!}

                                            <x-admin::form.control-group.control
                                                type="hidden"
                                                name="id"
                                                v-model="selectedCentre.id"
                                            >
                                            </x-admin::form.control-group.control>
                                            <div class="flex gap-6 mb-[10px]">
                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.name')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="name"
                                                        rules="required"
                                                        v-model="selectedCentre.name"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.name')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.name')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="name"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>

                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.address')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        as="textarea"
                                                        name="address"
                                                        rules="required"
                                                        v-model="selectedCentre.address"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.address')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.address')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="address"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>
                                            </div>

                                            <div class="flex gap-6 mb-[10px]">
                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.landmark')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="landmark"
                                                        rules="required"
                                                        v-model="selectedCentre.landmark"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.landmark')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.landmark')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="landmark"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>

                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.city')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="city"
                                                        rules="required"
                                                        v-model="selectedCentre.city"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.city')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.city')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="city"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>
                                            </div>
                                            <div class="flex gap-6 mb-[10px]">
                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.country')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="select"
                                                        id="country_code"
                                                        name="country_code"
                                                        rules="required"
                                                        v-model="selectedCentre.country_code"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.country')"
                                                    >
                                                        <!-- Default Option -->
                                                        <option value="">
                                                            @lang('pickup::app.admin.settings.pickup.index.create.select-country')
                                                        </option>

                                                        <option
                                                            v-for="country in countries"
                                                            :value="country.code"
                                                        >
                                                            @{{ country.name }}
                                                        </option>
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error control-name="country_code" />
                                                    <input type="hidden" name="country_id" :value="country_id" />
                                                </x-admin::form.control-group>


                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.state')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="select"
                                                        id="state_code"
                                                        name="state_code"
                                                        rules="required"
                                                        v-model="selectedCentre.state_code"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.edit.state')"
                                                    >
                                                        <!-- Default Option -->
                                                        <option value="">
                                                            @lang('pickup::app.admin.settings.pickup.index.create.select-state')
                                                        </option>

                                                        <option
                                                            v-for="state in statesByCountry[selectedCentre.country_code]"
                                                            :value="state.code"
                                                        >
                                                            @{{ state.default_name }}
                                                        </option>
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error control-name="state_code" />
                                                    <input type="hidden" name="state_id" :value="state_id" />
                                                </x-admin::form.control-group>
                                            </div>
                                            <div class="flex gap-6 mb-[10px]">
                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('pickup::app.admin.settings.pickup.index.create.phone')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="phone"
                                                        rules="required"
                                                        v-model="selectedCentre.phone"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.phone')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.phone')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="phone"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>

                                                <x-admin::form.control-group class="w-full">
                                                    <x-admin::form.control-group.label>
                                                        @lang('pickup::app.admin.settings.pickup.index.create.whatsapp')
                                                    </x-admin::form.control-group.label>

                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="whatsapp"
                                                        v-model="selectedCentre.whatsapp"
                                                        :label="trans('pickup::app.admin.settings.pickup.index.create.whatsappy')"
                                                        :placeholder="trans('pickup::app.admin.settings.pickup.index.create.whatsapp')"
                                                    >
                                                    </x-admin::form.control-group.control>

                                                    <x-admin::form.control-group.error
                                                        control-name="whatsapp"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>
                                            </div>
                                            <x-admin::form.control-group class="w-full">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('pickup::app.admin.settings.pickup.index.create.rate')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="number"
                                                    name="rate"
                                                    rules="required"
                                                    v-model="selectedCentre.rate"
                                                    :label="trans('pickup::app.admin.settings.pickup.index.create.rate')"
                                                    :placeholder="trans('pickup::app.admin.settings.pickup.index.create.rate')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="rate"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <x-admin::form.control-group class="w-full">
                                                <x-admin::form.control-group.label>
                                                    @lang('pickup::app.admin.settings.pickup.index.create.location')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="location"
                                                    v-model="selectedCentre.location"
                                                    :label="trans('pickup::app.admin.settings.pickup.index.create.location')"
                                                    :placeholder="trans('pickup::app.admin.settings.pickup.index.create.location')"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="location"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('pickup::app.admin.settings.pickup.index.create.additional')
                                                </x-admin::form.control-group.label>


                                                <v-business-hours
                                                    name="additional"
                                                    rules="required"
                                                    v-model="selectedCentre.additional"
                                                >
                                                </v-business-hours>

                                                <x-admin::form.control-group.error
                                                    control-name="additional"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <!-- Status -->
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.label>
                                                    @lang('pickup::app.admin.settings.pickup.index.create.status')
                                                </x-admin::form.control-group.label>

                                                @php $selectedValue = old('status', 0); @endphp

                                                <x-admin::form.control-group.control
                                                    type="switch"
                                                    name="status"
                                                    v-model="selectedCentre.status"
                                                    :label="trans('pickup::app.admin.settings.pickup.index.create.status')"
                                                    :placeholder="trans('pickup::app.admin.settings.pickup.index.create.status')"
                                                    ::checked="selectedCentre.status"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="status"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            {!! view_render_event('bagisto.admin.settings.pickup.create.after') !!}
                                        </div>
                                        <div class="flex p-4 gap-2 items-center">
                                            <button
                                                type="submit"
                                                class="primary-button justify-center w-1/2"
                                            >
                                                @lang('pickup::app.admin.settings.pickup.index.create.save-btn')
                                            </button>
                                            <button 
                                                type="button" 
                                                class="secondary-button justify-center w-1/2" 
                                                @click="id=0; selectedCentre={}; $refs.centreUpdateOrCreateDrawer.toggle()">
                                                @lang('pickup::app.admin.settings.pickup.index.create.cancel-btn')
                                            </button>
                                        </div>
                                    </form>
                                </x-admin::form>
                            </x-slot>
                        </x-admin::drawer>
                    @endif
                </div>
            </div>

            <x-admin::datagrid
                :src="route('admin.settings.pickup.index')"
                ref="datagrid"
            >
                <template #header="{ available, applied, sortPage, columns, records, performAction }">
                    <div
                        class="row grid gap-2.5 min-h-[47px] px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold items-center"
                        :style="`grid-template-columns: repeat(6, 1fr)`"
                    >
                        <!-- Columns -->
                        <p
                            v-for="column in columns.filter((column) => !['country_code', 'state_code'].includes(column.index))"
                            class="flex gap-1.5 items-center break-words"
                            :class="{'cursor-pointer select-none hover:text-gray-800 dark:hover:text-white': column.sortable}"
                            @click="sortPage(column)"
                        >
                            @{{ column.label }}

                            <i
                                class="text-base  text-gray-600 dark:text-gray-300 align-text-bottom"
                                :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                v-if="column.index == applied.sort.column"
                            ></i>
                        </p>

                        <!-- Actions -->
                        <p
                            class="place-self-end"
                            v-if="available.actions.length"
                        >
                            @lang('admin::app.components.datagrid.table.actions')
                        </p>
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 6 : 4) + ', 1fr);'"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Name -->
                        <p v-text="record.name"></p>

                        <!-- City -->
                        <p v-text="record.city"></p>

                        <!-- Rate -->
                        <p v-text="record.rate"></p>

                        <!-- Status -->
                        <p v-text="record.status"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            @if (bouncer()->hasPermission('settings.pickup.edit'))
                                <a @click="id=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
                            @if (bouncer()->hasPermission('settings.pickup.delete'))
                                <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                    <span
                                        :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>
        </script>

        <script type="text/x-template" id="v-business-hours-template">
            <div v-if="model" class="w-full">
                <div v-for="item in Object.keys(days)" :key="item" class="grid grid-cols-5 gap-3 py-2 border-b">
                    <div role="cell" class="flex justify-between items-center col-span-2">
                        <div class="w-full">
                            <label :for="`${name}[${item}][status]`"  class="inline-flex w-full cursor-pointer items-center justify-between gap-2 rounded-md border border-neutral-300 bg-neutral-50 px-2 py-1.5">
                                <input :id="`${name}[${item}][status]`" :name="`${name}[${item}][status]`" type="checkbox" v-model="model[item].status" class="peer sr-only" role="switch" true-value="on" false-value="off" :checked="model[item].status == 'on'" />
                                <span class="trancking-wide text-sm font-medium text-neutral-600 peer-checked:text-neutral-900 peer-disabled:cursor-not-allowed peer-disabled:opacity-70" v-text="days[item]"></span>
                                <div class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-neutral-300 bg-white after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-neutral-600 after:transition-all after:content-[''] peer-checked:bg-black peer-checked:after:bg-neutral-100 peer-focus:outline peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-neutral-800 peer-focus:peer-checked:outline-black peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70" aria-hidden="true"></div>
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center justify-between col-span-3">
                        <select
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border  rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            v-model="model[item].opens"
                            :name="`${name}[${item}][opens]`"
                            :disabled="model[item].status != 'on'" >
                            <option value="">Opening Time</option>
                            <template v-for="(val, _index) in timeRange">
                                <option :value="val">@{{ val }}:00</option>
                            </template>
                        </select>
                        <span>-</span>
                        <select
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            v-model="model[item].closes"
                            :name="`${name}[${item}][closes]`"
                            :disabled="model[item].status != 'on'" >
                            <option value="">Closing Time</option>
                            <template v-for="(val, _index) in timeRange">
                                <option :value="val">@{{ val }}:00</option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>
        </script>

    <script type="module">
        app.component('v-centres', {
            template: '#v-centres-template',

            data() {
                return {
                    selectedCentre: {
                        name: `{{ old('name') ?? "null" }}`,
                        city: `{{ old('city') ?? "null" }}`,
                        phone: `{{ old('phone') ?? "null" }}`,
                        address: `{{ old('address') ?? "null"}}`,
                        landmark: `{{ old('landmark') ?? "null" }}`,
                        rate: `{{ old('rate') ?? "null" }}`,
                        location: `{{ old('location') ?? "null" }}`,
                        whatsapp: `{{ old('whatsapp') ?? "null" }}`,
                        status: `{{ old('status') ?? "null" }}`,
                        additional: `{{ old('additional') ?? "null" }}`,
                        state_code: `{{ old('state_code') ?? $setState?->code }}`,
                        country_code: `{{ old('country_code') ?? $setCountry->code }}`,
                    },
                }
            },

            computed: {
                statesByCountry() {
                    return @json(core()->groupedStatesByCountries());
                },
                countries() {
                    const countries =  @json($countries);
                    const supported = Object.keys(this.statesByCountry);
                    return countries.filter(item => supported.includes(item.code));
                },
                country_id() {
                    return this.countries?.find(item => item.code == this.selectedCentre.country_code)?.id;
                },
                state_id() {
                    return this.statesByCountry[this.selectedCentre.country_code]?.find(item => item.code == this.selectedCentre.state_code)?.id;
                }
            },

            methods: {
                updateOrCreate(params, {
                    resetForm,
                    setErrors
                }) {
                    let formData = new FormData(this.$refs.pickupCreateForm);

                    if (params.id) {
                        formData.append('_method', 'put');
                    }

                    const status = formData.get('status');

                    if (!status) {
                        formData.append('status', 0);                        
                    } else if (status == 'on') {
                        formData.set('status', 1)
                    }

                    console.log({ params, formData, model: this.selectedCentre })

                    this.$axios.post(params.id ? "{{ route('admin.settings.pickup.update') }}" : "{{ route('admin.settings.pickup.store') }}", formData)
                        .then((response) => {
                            //this.$refs.centreUpdateOrCreateModal.close();

                            this.$refs.datagrid.get();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });

                            //resetForm();
                            this.$refs.centreUpdateOrCreateDrawer.toggle();
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                editModal(url) {
                    this.$axios.get(url)
                        .then((response) => {
                            this.selectedCentre = response.data;

                            this.$refs.centreUpdateOrCreateDrawer.toggle();
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response.data.message
                            })
                        });
                },

                setFilters({ available, applied }) {
                    const filterColumns = applied.filters.columns;
                    const isSet = (key) => Array.isArray(filterColumns) && filterColumns.find(item => item.index == key)

                    const columns = available.columns;
                    const country_code = columns.find(item => item.databaseColumnName == 'country_code');

                    if (country_code && !isSet('country_code')) {
                        this.$refs.datagrid.applyFilter(country_code, `{{ $countryCode }}`);
                    }

                    const state_code = columns.find(item => item.databaseColumnName == 'state_code')
                    if (state_code && !isSet('state_code')) {
                        this.$refs.datagrid.applyFilter(state_code, `{{ $stateCode }}`)
                    }
                },

                registerEvents() {
                    this.$emitter.on('change-datagrid', this.setFilters);
                },
            },

            mounted() {
                this.registerEvents();
            }
        });

        app.component('v-business-hours', {
            template: '#v-business-hours-template',
            props: ['modelValue', 'name'],
            data() {
                return {
                    model: null,
                    days: {
                        monday: 'Monday',
                        tuesday: 'Tuesday',
                        wednesday: 'Wednesday',
                        thursday: 'Thursday',
                        friday: 'Friday',
                        saturday: 'Saturday',
                        sunday: 'Sunday'
                    }
                }
            },
            computed: {
                timeRange() {
                    return new Array(24).fill(undefined).map((_, i) => i + 1)
                }
            },
            mounted() {
                this.model = Object.keys(this.days).reduce((acc, day) => ({
                    ...acc, 
                    [day]: !this.modelValue || !this.modelValue[day]?.status ?  {  "opens": "9",  "closes": "17", "status": 'off' } : this.modelValue[day]
                }), {});
                            },
            watch: {
                model: {
                    handler(newValue) {
                        this.$emit('update:modelValue', newValue)
                    },
                    deep: true
                }
            },
        })
    </script>
    @endPushOnce
</x-admin::layouts>
