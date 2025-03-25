@php
    $countryCode = request('country', config('app.default_country'));
    $countries = core()->countries();
    $setCountry = $countries->firstWhere('code', $countryCode);

    $states = core()->states(request('country', config('app.default_country')));
    $setState = request('state') ? $states->firstWhere('code', request('state')) : $states->first();
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
                        <x-admin::drawer width="350px">
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

                                            <x-admin::form.control-group class="mb-[10px]">
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

                                            <x-admin::form.control-group class="mb-[10px]">
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

                                            <x-admin::form.control-group class="mb-[10px]">
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

                                            <x-admin::form.control-group class="mb-[10px]">
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

                                            <x-admin::form.control-group class="mb-[10px]">
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

                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('pickup::app.admin.settings.pickup.index.create.whatsapp')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="whatsapp"
                                                    rules="required"
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

                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('pickup::app.admin.settings.pickup.index.create.rate')
                                                </x-admin::form.control-group.label>

                                                <x-admin::form.control-group.control
                                                    type="number"
                                                    name="rate"
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

                                            <x-admin::form.control-group class="mb-[10px]">
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
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label class="required">
                                                    @lang('pickup::app.admin.settings.pickup.index.create.additional')
                                                </x-admin::form.control-group.label>


                                                <v-business-hours
                                                    name="additional"
                                                    rules="required"
                                                    :value="selectedCentre.additional"
                                                >
                                                </v-business-hours>

                                                <x-admin::form.control-group.error
                                                    control-name="additional"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>

                                            <!-- Status -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('pickup::app.admin.settings.pickup.index.create.status')
                                                </x-admin::form.control-group.label>

                                                @php $selectedValue = old('status', true); @endphp

                                                <x-admin::form.control-group.control
                                                    type="switch"
                                                    name="status"
                                                    v-model="selectedCentre.status"
                                                    :label="trans('pickup::app.admin.settings.pickup.index.create.status')"
                                                    :placeholder="trans('pickup::app.admin.settings.pickup.index.create.status')"
                                                    :checked="(boolean) $selectedValue"
                                                >
                                                </x-admin::form.control-group.control>

                                                <x-admin::form.control-group.error
                                                    control-name="status"
                                                >
                                                </x-admin::form.control-group.error>
                                            </x-admin::form.control-group>
                                            <x-admin::form.control-group.control type="hidden" name="country_id" :value="$setCountry->id"> </x-admin::form.control-group.control>
                                            <x-admin::form.control-group.control type="hidden" name="country_code" :value="$setCountry->code"> </x-admin::form.control-group.control>
                                            <x-admin::form.control-group.control type="hidden" name="state_id" :value="$setState->id"> </x-admin::form.control-group.control>
                                            <x-admin::form.control-group.control type="hidden" name="state_code" :value="$setState->code"> </x-admin::form.control-group.control>

                                            {!! view_render_event('bagisto.admin.settings.pickup.create.after') !!}
                                        </div>
                                    </form>
                                </x-admin::form>
                            </x-slot>
                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('pickup::app.admin.settings.pickup.index.create.save-btn')
                                    </button>
                                </div>                                    
                            </x-slot:footer>
                        </x-admin::drawer>
                    @endif
                </div>
            </div>

            <x-admin::datagrid
                :src="route('admin.settings.pickup.index')"
                ref="datagrid"
            >
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
            <div class="w-full overflow-y-auto h-96 px-4">
                <div v-for="item in Object.keys(model)" :key="item" class="grid grid-cols-5 gap-3 mb-2 border-b">
                    <div role="cell" class="flex justify-between mb-2 col-span-2">
                        <div class="w-full">
                            <label :for="`${name}[${item}][isOpen]`"  class="inline-flex w-full cursor-pointer items-center justify-between gap-2 rounded-md border border-neutral-300 bg-neutral-50 px-2 py-1.5">
                                <input :id="`${name}[${item}][isOpen]`" :name="`${name}[${item}][isOpen]`" type="checkbox" v-model="model[item].isOpen" class="peer sr-only" role="switch" true-value="on" false-value="off" :checked="model[item].isOpen" />
                                <span class="trancking-wide text-sm font-medium text-neutral-600 peer-checked:text-neutral-900 peer-disabled:cursor-not-allowed peer-disabled:opacity-70" v-text="item.charAt(0).toUpperCase() + item.slice(1)"></span>
                                <div class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-neutral-300 bg-white after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-neutral-600 after:transition-all after:content-[''] peer-checked:bg-black peer-checked:after:bg-neutral-100 peer-focus:outline peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-neutral-800 peer-focus:peer-checked:outline-black peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70" aria-hidden="true"></div>
                            </label>
                        </div>
                    </div>
                    <div v-show="model[item].isopen" class="flex gap-2 items-center justify-between">
                        <select
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border  rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            v-model="model[item].open"
                            :name="`${name}[${item}][open]`">
                            <option value="">Opens</option>
                            <template v-for="(val, _index) in timeRange">
                                <option :value="val" v-text="val"></option>
                            </template>
                        </select>
                        <span>-</span>
                        <select
                            class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            v-model="model[item].close"
                            :name="`${name}{$item}[close]`">
                            <option value="">Closes</option>
                            <template v-for="(val, _index) in timeRange">
                                <option :value="val" v-text="val"></option>
                            </template>
                        </select>
                    </div>
                    <div v-show="false" class="flex-auto p-16 w-14">
                        <input :name="`${name}[${item}][groupWithFormer]`" type="checkbox" v-model="model[item].groupWithFormer"> <label>Group</label>
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
                        name: {{ old('name') ?? "null" }},
                        city: {{ old('city') ?? "null" }},
                        phone: {{ old('phone') ?? "null" }},
                        address: {{ old('address') ?? "null"}},
                        landmark: {{ old('landmark') ?? "null" }},
                        rate: {{ old('rate') ?? "null" }},
                        location: {{ old('location') ?? "null" }},
                        whatsapp: {{ old('whatsapp') ?? "null" }},
                        status: {{ old('status') ?? "null" }},
                        additional: {{ old('additional') ?? "null" }},
                    },
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

                    this.$axios.post(params.id ? "{{ route('admin.settings.pickup.update') }}" : "{{ route('admin.settings.pickup.store') }}", formData)
                        .then((response) => {
                            //this.$refs.centreUpdateOrCreateModal.close();

                            this.$refs.datagrid.get();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });

                            resetForm();
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

                            //this.$refs.centreUpdateOrCreateModal.toggle();
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
                        this.$refs.datagrid.applyFilter(country_code, `{{ config('app.default_country') }}`);
                    }

                    const state_code = columns.find(item => item.databaseColumnName == 'state_code')
                    if (state_code && !isSet('state_code')) {
                        this.$refs.datagrid.applyFilter(state_code, `{{ config('app.default_state') }}`)
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
            props: ['value', 'name'],
            data() {
                const defaultValue = {
                        "monday": {
                            "open": "",
                            "close": "",
                            "isOpen": true
                        },
                        "tuesday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                        "wednesday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                        "thursday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                        "friday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                        "saturday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                        "sunday": {
                            "open": "",
                            "close": "",
                            "isOpen": true,
                            "groupWithFormer": false,
                        },
                    };
                return {
                    model: this.value ? this.value : defaultValue,
                }
            },
            computed: {
                timeRange() {
                    return new Array(24).fill(undefined).map((_, i) => i)
                }
            },

            watch: {
                model: {
                    handler(newValue) {
                        this.$emit('change', newValue)
                    },
                    deep: true
                }
            },
        })
    </script>
    @endPushOnce
</x-admin::layouts>
