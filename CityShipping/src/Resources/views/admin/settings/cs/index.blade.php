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
        @lang('cs::app.admin.settings.cs.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.cs.create.before') !!}

    <v-cities>
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('cs::app.admin.settings.cs.index.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Craete currency Button -->
                @if (bouncer()->hasPermission('settings.cs.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('cs::app.admin.settings.cs.index.create-btn')
                    </button>
                @endif
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-cities>

    {!! view_render_event('bagisto.admin.settings.cs.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-cities-template"
        >
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('cs::app.admin.settings.cs.index.title')
                </p>

                <div class="flex gap-x-2.5 items-center">
                    <!-- Craete currency Button -->
                    @if (bouncer()->hasPermission('settings.cs.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="id=0; selectedCity={}; $refs.cityUpdateOrCreateModal.toggle()"
                        >
                            @lang('cs::app.admin.settings.cs.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <x-admin::datagrid
                :src="route('admin.settings.cs.index')"
                ref="datagrid"
            >
                <template #header="{ available, applied, sortPage, columns, records, performAction }">
                    <div
                        class="row grid gap-2.5 min-h-[47px] px-4 py-2.5 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold items-center"
                        :style="`grid-template-columns: repeat(5, 1fr)`"
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
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 5 : 4) + ', 1fr);'"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Code -->
                        <p v-text="record.name"></p>

                        <!-- Name -->
                        <p v-text="record.rate"></p>

                        <!-- Status -->
                        <p v-text="record.status"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            @if (bouncer()->hasPermission('settings.cs.edit'))
                                <a @click="id=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                    <span
                                        :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
                            @if (bouncer()->hasPermission('settings.cs.delete'))
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

            <!-- Modal Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="cityCreateForm"
                >
                    <x-admin::modal ref="cityUpdateOrCreateModal">
                        <x-slot:header>
                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-if="id"
                            >
                                @lang('cs::app.admin.settings.cs.index.edit.title')
                            </p>

                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('cs::app.admin.settings.cs.index.create.title')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-4 py-1.5 dark:border-gray-800">
                                {!! view_render_event('bagisto.admin.settings.cs.create.before') !!}

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="selectedCity.id"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('cs::app.admin.settings.cs.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        v-model="selectedCity.name"
                                        :label="trans('cs::app.admin.settings.cs.index.create.name')"
                                        :placeholder="trans('cs::app.admin.settings.cs.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('cs::app.admin.settings.cs.index.create.rate')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rate"
                                        rules="required"
                                        v-model="selectedCity.rate"
                                        :label="trans('cs::app.admin.settings.cs.index.create.rate')"
                                        :placeholder="trans('cs::app.admin.settings.cs.index.create.rate')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="rate"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('cs::app.admin.settings.cs.index.create.country')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="country_code"
                                        name="country_code"
                                        rules="required"
                                        v-model="selectedCity.country_code"
                                        :label="trans('cs::app.admin.settings.cs.index.create.country')"
                                    >
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('cs::app.admin.settings.cs.index.create.select-country')
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


                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('cs::app.admin.settings.cs.index.create.state')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state_code"
                                        name="state_code"
                                        rules="required"
                                        v-model="selectedCity.state_code"
                                        :label="trans('cs::app.admin.settings.cs.index.create.state')"
                                    >
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('cs::app.admin.settings.cs.index.create.select-state')
                                        </option>

                                        <option
                                            v-for="state in statesByCountry[selectedCity.country_code]"
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="state_code" />
                                    <input type="hidden" name="state_id" :value="state_id" />
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('cs::app.admin.settings.cs.index.create.status')
                                    </x-admin::form.control-group.label>

                                    @php $selectedValue = old('status', true); @endphp

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        v-model="selectedCity.status"
                                        :label="trans('cs::app.admin.settings.cs.index.create.status')"
                                        :placeholder="trans('cs::app.admin.settings.cs.index.create.status')"
                                        ::checked="selectedCity.status"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>


                                {!! view_render_event('bagisto.admin.settings.cs.create.after') !!}
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-2.5 items-center">
                               <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('cs::app.admin.settings.cs.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-cities', {
                template: '#v-cities-template',

                data() {
                    return {
                        selectedCity: {
                            name: `{{ old('name') ?? "null" }}`,
                            rate: `{{ old('rate') ?? "null" }}`,
                            status: `{{ old('status') ?? "null" }}`,
                            state_code: "{{ old('state_code') ?? $setState?->code }}",
                            country_code: "{{ old('country_code') ?? $setCountry->code }}",
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
                        return this.countries?.find(item => item.code == this.selectedCity.country_code)?.id;
                    },
                    state_id() {
                        return this.statesByCountry[this.selectedCity.country_code]?.find(item => item.code == this.selectedCity.state_code)?.id;
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors  }) {
                        let formData = new FormData(this.$refs.cityCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }
                        
                        const status = formData.get('status');

                        if (!status) {
                            formData.append('status', 0);                        
                        } else if (status == 'on') {
                            formData.set('status', 1)
                        }

                        this.$axios.post(params.id ? "{{ route('admin.settings.cs.update') }}" : "{{ route('admin.settings.cs.store') }}", formData)
                        .then((response) => {
                            this.$refs.cityUpdateOrCreateModal.close();

                            this.$refs.datagrid.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status ==422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.selectedCity = response.data;

                                this.$refs.cityUpdateOrCreateModal.toggle();
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message })
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
                    this.selectedCity = {
                            name: `{{ old('name') ?? "null" }}`,
                            rate: `{{ old('rate') ?? "null" }}`,
                            status: `{{ old('status') ?? "null" }}`,
                            state_code: "{{ old('state_code') ?? $setState?->code }}",
                            country_code: "{{ old('country_code') ?? $setCountry->code }}",
                        };
                    this.registerEvents();
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
