<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.cs.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.cs.create.before') !!}

    <v-cities>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.cs.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Craete currency Button -->
                @if (bouncer()->hasPermission('settings.cs.create'))
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.cs.index.create-btn')
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
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.settings.cs.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- Craete currency Button -->
                    @if (bouncer()->hasPermission('settings.cs.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="id=0; selectedCity={}; $refs.cityUpdateOrCreateModal.toggle()"
                        >
                            @lang('admin::app.settings.cs.index.create-btn')
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
                                    href="?{{ Arr::query(['country' => $country->code]) }}"
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
                        $states = core()->states($countryCode);
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
                :src="route('admin.settings.cs.index')"
                ref="datagrid"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('settings.cs.edit') || bouncer()->hasPermission('settings.cs.delete');
                @endphp

                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-{{ $hasPermission ? '4' : '3' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'code', 'name']"
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

                        <!-- Code -->
                        <p v-text="record.name"></p>

                        <!-- Name -->
                        <p v-text="record.rate"></p>

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
                    ref="cityCreateForm"
                >
                    <x-admin::modal ref="cityUpdateOrCreateModal">
                        <x-slot:header>
                            <p
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-if="id"
                            >
                                @lang('admin::app.settings.cs.index.edit.title')
                            </p>

                            <p
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('admin::app.settings.cs.index.create.title')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                {!! view_render_event('bagisto.admin.settings.cs.create.before') !!}

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                    v-model="selectedCity.id"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.cs.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        rules="required"
                                        v-model="selectedCity.name"
                                        :label="trans('admin::app.settings.cs.index.create.name')"
                                        :placeholder="trans('admin::app.settings.cs.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.cs.index.create.rate')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rate"
                                        :value="old('rate')"
                                        rules="required"
                                        v-model="selectedCity.rate"
                                        :label="trans('admin::app.settings.cs.index.create.rate')"
                                        :placeholder="trans('admin::app.settings.cs.index.create.rate')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="rate"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Status -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.inventory-sources.edit.status')
                                    </x-admin::form.control-group.label>

                                    @php $selectedValue = old('status') ?: $inventorySource->status; @endphp

                                    <x-admin::form.control-group.control
                                        type="switch"
                                        name="status"
                                        :value="old('status') ?? ($inventorySource->status)"
                                        :label="trans('admin::app.settings.inventory-sources.edit.status')"
                                        :placeholder="trans('admin::app.settings.inventory-sources.edit.status')"
                                        :checked="(boolean) $selectedValue"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                <input type="hidden" name="country_id" value="{{ $setCountry->id }}"/>
                                <input type="hidden" name="country_code" value="{{ $setCountry->code }}"/>
                                <input type="hidden" name="state_id" value="{{ $setState->id }}"/>
                                <input type="hidden" name="state_code" value="{{ $setState->code }}"/>

                                {!! view_render_event('bagisto.admin.settings.cs.create.after') !!}
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                               <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.settings.cs.index.create.save-btn')
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
                        selectedCity: {},
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors  }) {
                        let formData = new FormData(this.$refs.cityCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
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
                }
            })
        </script>
    @endPushOnce
</x-admin::layouts>
