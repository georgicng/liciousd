<x-admin::layouts>
    <x-slot:title>
        @lang('pickup::app.admin.settings.pickup.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.pickup.create.before') !!}

    <v-centres>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('pickup::app.admin.settings.pickup.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
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
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('pickup::app.admin.settings.pickup.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- Craete currency Button -->
                    @if (bouncer()->hasPermission('settings.pickup.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="id=0; selectedCentre={}; $refs.centreUpdateOrCreateModal.toggle()"
                        >
                            @lang('pickup::app.admin.settings.pickup.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>

            <div class="flex  gap-[16px] justify-between items-center mt-[28px] max-md:flex-wrap">
                <div class="flex gap-x-[4px] items-center">
                    @php
                        $countryCode = request('country', config('app.default_country'));
                        $countries = core()->countries();
                        $setCountry = $countries->firstWhere('code', $countryCode);
                    @endphp
                    {{-- Country Switcher --}}
                    <x-admin::dropdown>
                        {{-- Dropdown Toggler --}}
                        <x-slot:toggle>
                            <button
                                type="button"
                                class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                            >
                                <span class="icon-language text-[24px] "></span>

                                {{ $setCountry->name }}

                                <input type="hidden" name="country" value="{{ $setCountry->code }}"/>

                                <span class="icon-sort-down text-[24px]"></span>
                            </button>
                        </x-slot:toggle>

                        {{-- Dropdown Content --}}
                        <x-slot:content class="!p-[0px]">
                            @foreach ($countries as $country)
                                <a
                                    href="?{{ Arr::query(['cid' => $country->code]) }}"
                                    class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white"
                                >
                                    {{ $country->name }}
                                </a>
                            @endforeach
                        </x-slot:content>
                    </x-admin::dropdown>
                </div>
                <div class="flex gap-x-[4px] items-center">
                    @php
                        $states = core()->states(request('country', config('app.default_country')));
                        $setState = request('state') ? $states->firstWhere('code', request('state')) : $states->first();
                    @endphp
                    {{-- State Switcher --}}
                    <x-admin::dropdown>
                        {{-- Dropdown Toggler --}}
                        <x-slot:toggle>
                            <button
                                type="button"
                                class="transparent-button px-[4px] py-[6px] hover:bg-gray-200 dark:hover:bg-gray-800 focus:bg-gray-200 dark:focus:bg-gray-800 dark:text-white"
                            >
                                <span class="icon-language text-[24px] "></span>

                                {{ $setState->default_name ?? '' }}

                                <input type="hidden" name="state" value="{{ $setState->code ?? '' }}"/>

                                <span class="icon-sort-down text-[24px]"></span>
                            </button>
                        </x-slot:toggle>

                        {{-- Dropdown Content --}}
                        <x-slot:content class="!p-[0px]">
                            @foreach ($states as $state)
                                <a
                                    href="?{{ Arr::query(['state' => $state->code, 'country' => $countryCode]) }}"
                                    class="flex gap-[10px] px-5 py-2 text-[16px] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white"
                                >
                                    {{ $state->default_name }}
                                </a>
                            @endforeach
                        </x-slot:content>
                    </x-admin::dropdown>
                </div>
            </div>

            <x-admin::datagrid
                :src="route('admin.settings.pickup.index', empty($setState) ? [] : ['state' => $setState->code, 'country' => $countryCode])"
                ref="datagrid"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('settings.pickup.edit') || bouncer()->hasPermission('settings.pickup.delete');
                @endphp

                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-{{ $hasPermission ? '4' : '3' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'name', 'city', 'rate', 'status']"
                        >
                            <p class="text-gray-600 dark:text-gray-300">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 dark:text-white font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                        }"
                                        @click="
                                            columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                        "
                                    >
                                        @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                    </span>
                                </span>

                                <!-- Filter Arrow Icon -->
                                <i
                                    class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 dark:text-white align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        @if ($hasPermission)
                            <p class="flex gap-[10px] justify-end">
                                @lang('admin::app.components.datagrid.table.actions')
                            </p>
                        @endif
                    </div>
                </template>

                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 4 : 3) + ', 1fr);'"
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
                            <a @click="id=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                <span
                                    :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                >
                                </span>
                            </a>

                            <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                <span
                                    :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                >
                                </span>
                            </a>
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
                    ref="currencyCreateForm"
                >
                    <x-admin::modal ref="centreUpdateOrCreateModal">
                        <x-slot:header>
                            <p
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-if="id"
                            >
                                @lang('pickup::app.admin.settings.pickup.index.edit.title')
                            </p>

                            <p
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('pickup::app.admin.settings.pickup.index.create.title')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
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
                        </x-slot:content>

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
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>
        <script type="text/x-template" id="v-business-hours-template">
            <div class="overflow-y-auto h-32">
                <div v-for="item in Object.keys(model)" :key="item" class="flex">
                    <div role="cell" class="flex-auto p-16 w-14">
                        <div>@{{ item.charAt(0).toUpperCase() + item.slice(1)}}</div>
                    </div>
                    <div class="flex-auto p-16 w-14">
                        <input :name="`${name}[${item}][isOpen]`" type="checkbox" v-model="model[item].isOpen" true-value="on" false-value="off"> <label>Open</label>
                    </div>
                    <div v-show="model[item].isOpen" class="flex-auto p-16">
                        <input :name="`${name}[${item}][open]`" type="text" v-model="model[item].open" placeholder="Opens"> - <input :name="`${name}[${item}][close]`" type="text" v-model="model[item].close" placeholder="Closes">
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
                    let formData = new FormData(this.$refs.currencyCreateForm);

                    if (params.id) {
                        formData.append('_method', 'put');
                    }

                    this.$axios.post(params.id ? "{{ route('admin.settings.pickup.update') }}" : "{{ route('admin.settings.pickup.store') }}", formData)
                        .then((response) => {
                            this.$refs.centreUpdateOrCreateModal.close();

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

                            this.$refs.centreUpdateOrCreateModal.toggle();
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response.data.message
                            })
                        });
                },
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
                    console.log({ value: this.value })
                return {
                    model: this.value ? this.value : defaultValue
                }
            },

            watch: {
                model(newValue) {
                    this.$emit('change', newValue)
                },
                deep: true
            }
        })
    </script>
    @endPushOnce
</x-admin::layouts>
