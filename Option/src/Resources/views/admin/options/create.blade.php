@php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('option::app.admin.catalog.options.create.title')
    </x-slot:title>

    {{-- Create Attributes Vue Components --}}
    <v-create-attributes :all-locales="{{ $allLocales->toJson() }}"></v-create-attributes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-attributes-template"
        >

            {!! view_render_event('bagisto.admin.options.create.before') !!}

            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.options.store')"
                enctype="multipart/form-data"
            >

                {!! view_render_event('bagisto.admin.options.create.create_form_controls.before') !!}

                <!-- actions buttons -->
                <div class="flex justify-between items-center">
                    <p class="text-xl text-gray-800 dark:text-white font-bold">
                        @lang('option::app.admin.catalog.options.create.title')
                    </p>

                    <div class="flex gap-x-2.5 items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.options.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                        >
                            @lang('option::app.admin.catalog.options.create.back-btn')
                        </a>

                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('option::app.admin.catalog.options.create.save-btn')
                        </button>
                    </div>
                </div>

                <!-- body content -->
                <div class="flex gap-2.5 mt-3.5">

                    {!! view_render_event('bagisto.admin.options.create.card.label.before') !!}

                    <!-- Left sub Component -->
                    <div class="flex flex-col gap-2 flex-1 overflow-auto">
                        <!-- Label -->
                        <div class="p-4 bg-white dark:bg-gray-900 box-shadow rounded">
                            <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('option::app.admin.catalog.options.create.label')
                            </p>

                            <!-- Admin name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('option::app.admin.catalog.options.create.admin')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    :value="old('admin_name')"
                                    rules="required"
                                    :label="trans('option::app.admin.catalog.options.create.admin')"
                                    :placeholder="trans('option::app.admin.catalog.options.create.admin')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="admin_name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Locales Inputs -->
                            @foreach ($allLocales as $locale)
                                <x-admin::form.control-group class="last:!mb-0">
                                    <x-admin::form.control-group.label>
                                        {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        :name="$locale->code . '[name]'"
                                        :value="old($locale->code . '[name]')"
                                        :placeholder="$locale->name"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        :control-name="$locale->code . '[name]'"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            @endforeach
                        </div>

                        <!-- Options -->
                        <div
                            class="p-4 bg-white dark:bg-gray-900 box-shadow rounded"
                            v-if="swatchAttribute && (
                                    attributeType == 'select'
                                    || attributeType == 'multiselect'
                                    || attributeType == 'price'
                                    || attributeType == 'checkbox'
                                )"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                                    @lang('option::app.admin.catalog.options.create.title')
                                </p>

                                <!-- Add Row Button -->
                                <div
                                    class="secondary-button text-sm"
                                    @click="$refs.addOptionsRow.toggle()"
                                >
                                    @lang('option::app.admin.catalog.options.create.add-row')
                                </div>
                            </div>

                            <!-- For Attribute Options If Data Exist -->
                            <div class="mt-[15px] overflow-x-auto">
                                <template v-if="this.options?.length">
                                    <div class="flex gap-4 max-sm:flex-wrap">
                                        <x-admin::form.control-group class="w-full mb-2.5">
                                            <x-admin::form.control-group.label>
                                                @lang('option::app.admin.catalog.options.create.input-options')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="swatch_type"
                                                id="swatchType"
                                                :value="old('swatch_type')"
                                                v-model="swatchType"
                                                @change="showSwatch=true"
                                            >
                                                @foreach (['dropdown', 'color', 'image', 'text'] as $type)
                                                    <option value="{{ $type }}">
                                                        @lang('option::app.admin.catalog.options.create.option.' . $type)
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                class="mt-3"
                                                control-name="admin"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <div class="w-full mb-2.5">
                                            <!-- checkbox -->
                                            <x-admin::form.control-group.label class="invisible">
                                                @lang('option::app.admin.catalog.options.create.input-options')
                                            </x-admin::form.control-group.label>

                                            <div class="flex gap-2.5 items-center w-max !mb-0 p-1.5 cursor-pointer select-none">
                                                <input
                                                    type="checkbox"
                                                    name="empty_option"
                                                    id="empty_option"
                                                    for="empty_option"
                                                    class="hidden peer"
                                                    v-model="isNullOptionChecked"
                                                    @click="$refs.addOptionsRow.toggle()"
                                                />

                                                <label
                                                    for="empty_option"
                                                    class="icon-uncheckbox text-2xl rounded-md cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600"
                                                >
                                                </label>

                                                <label
                                                    for="empty_option"
                                                    class="text-sm text-gray-600 dark:text-gray-300 font-semibold cursor-pointer"
                                                >
                                                    @lang('option::app.admin.catalog.options.create.create-empty-option')
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table Information -->                                    
                                    <x-admin::table>
                                        <x-admin::table.thead class="text-sm font-medium dark:bg-gray-800">
                                            <x-admin::table.thead.tr>
                                                <x-admin::table.th class="!p-0"></x-admin::table.th>

                                                <!-- Swatch Select -->
                                                <x-admin::table.th v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                    @lang('option::app.admin.catalog.options.create.swatch')
                                                </x-admin::table.th>

                                                <!-- Admin tables heading -->
                                                <x-admin::table.th>
                                                    @lang('option::app.admin.catalog.options.create.admin-name')
                                                </x-admin::table.th>

                                                <!-- Loacles tables heading -->
                                                @foreach ($allLocales as $locale)
                                                    <x-admin::table.th>
                                                        {{ $locale->name . ' (' . $locale->code . ')' }}
                                                    </x-admin::table.th>
                                                @endforeach

                                                <!-- Action tables heading -->
                                                <x-admin::table.th></x-admin::table.th>
                                            </x-admin::table.thead.tr>
                                        </x-admin::table.thead>

                                        <!-- Draggable Component -->
                                        <draggable
                                            tag="tbody"
                                            ghost-class="draggable-ghost"
                                            v-bind="{animation: 200}"
                                            :list="options"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }">
                                                <x-admin::table.thead.tr class="hover:bg-gray-50 dark:hover:bg-gray-950">
                                                    <!-- Draggable Icon -->
                                                    <x-admin::table.td class="!px-0 text-center">
                                                        <i class="icon-drag text-xl transition-all group-hover:text-gray-700 cursor-grab"></i>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][position]'"
                                                            :value="index"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Swatch Type Image / Color -->
                                                    <x-admin::table.td v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                        <!-- Swatch Image -->
                                                        <div v-if="swatchType == 'image'">
                                                            <img
                                                                src="{{ bagisto_asset('images/product-placeholders/front.svg') }}"
                                                                :ref="'image_' + element.params.id"
                                                                class="h-[50px] w-[50px] dark:invert dark:mix-blend-exclusion"
                                                            />

                                                            <input
                                                                type="file"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                class="hidden"
                                                                :ref="'imageInput_' + element.id"
                                                            />
                                                        </div>

                                                        <!-- Swatch Color -->
                                                        <div v-if="swatchType == 'color'">
                                                            <div
                                                                class="w-[25px] h-[25px] border border-gray-200 dark:border-gray-800 rounded-[5px]"
                                                                :style="{ background: element.params.swatch_value }"
                                                            >
                                                            </div>

                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.params.swatch_value"
                                                            />
                                                        </div>
                                                    </x-admin::table.td>

                                                    <!-- Admin-->
                                                    <x-admin::table.td>
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.params.admin_name"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin_name]'"
                                                            v-model="element.params.admin_name"
                                                        />
                                                    </x-admin::table.td>

                                                    <x-admin::table.td v-for="locale in allLocales">
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.params[locale.code]"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][' + locale.code + '][label]'"
                                                            v-model="element.params[locale.code]"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Actions button -->
                                                    <x-admin::table.td class="!px-0">
                                                        <span
                                                            class="icon-edit p-1.5 rounded-md text-2xl] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="editModal(element)"
                                                        >
                                                        </span>

                                                        <span
                                                            class="icon-delete p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="removeOption(element.id)"
                                                        >
                                                        </span>
                                                    </x-admin::table.td>
                                                </x-admin::table.thead.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                    
                                </template>

                                <!-- For Empty Attribute Options -->
                                <template v-else>
                                    <div class="grid gap-3.5 justify-items-center py-10 px-2.5">
                                        <!-- Attribute Option Image -->
                                        <img
                                            class="w-[120px] h-[120px] dark:invert dark:mix-blend-exclusion"
                                            src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                            alt="@lang('option::app.admin.catalog.options.create.add-attribute-options')"
                                        />

                                        <!-- Add Attribute Options Information -->
                                        <div class="flex flex-col gap-1.5 items-center">
                                            <p class="text-base text-gray-400 font-semibold">
                                                @lang('option::app.admin.catalog.options.create.add-attribute-options')
                                            </p>

                                            <p class="text-gray-400">
                                                @lang('option::app.admin.catalog.options.create.add-options-info')
                                            </p>
                                        </div>

                                        <!-- Add Row Button -->
                                        <div
                                            class="secondary-button text-[14px]"
                                            @click="$refs.addOptionsRow.toggle()"
                                        >
                                            @lang('option::app.admin.catalog.options.create.add-row')
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.options.create.card.label.after') !!}

                    {!! view_render_event('bagisto.admin.options.create.card.general.before') !!}

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-2 w-[360px] max-w-full">
                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded">
                            <div class="flex justify-between items-center p-1.5">
                                <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                    @lang('option::app.admin.catalog.options.create.general')
                                </p>
                            </div>

                            <div class="px-4 pb-4">
                                 <!-- Attribute Code -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('option::app.admin.catalog.options.create.code')
                                    </x-admin::form.control-group.label>

                                    <v-field
                                        type="text"
                                        name="code"
                                        value="{{ old('code') }}"
                                        label="{{ trans('option::app.admin.catalog.options.create.code') }}"
                                        rules="required"
                                        v-slot="{ field }"
                                    >
                                        <input
                                            type="text"
                                            name="slug"
                                            id="code"
                                            v-bind="field"
                                            :class="[errors['{{ 'code' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                            class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:focus:border-gray-400 focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                            placeholder="{{ trans('option::app.admin.catalog.options.create.code') }}"
                                            v-code
                                        >
                                    </v-field>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Attribute Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('option::app.admin.catalog.options.create.type')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        :value="old('type')"
                                        id="type"
                                        class="cursor-pointer"
                                        :label="trans('option::app.admin.catalog.options.create.type')"
                                        v-model="attributeType"
                                        @change="swatchAttribute=true"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['text', 'textarea', 'boolean', 'select', 'multiselect', 'checkbox', 'file', 'json', 'date'] as $type)
                                            <option
                                                value="{{ $type }}"
                                                {{ $type === 'text' ? "selected" : '' }}
                                            >
                                                @lang('option::app.admin.catalog.options.create.'. $type)
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.admin.options.create.card.general.after') !!}

                </div>

                {!! view_render_event('bagisto.admin.options.create_form_controls.after') !!}
            </x-admin::form>

            <!-- Add Options Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modelForm"
            >
                <form
                    @submit.prevent="handleSubmit($event, storeOptions)"
                    enctype="multipart/form-data"
                    ref="createOptionsForm"
                >
                    <x-admin::modal
                        @toggle="listenModal"
                        ref="addOptionsRow"
                    >
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('option::app.admin.catalog.options.create.add-option')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="grid grid-cols-3 gap-4">
                                <!-- Hidden Id Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <!-- Admin Input -->
                                <x-admin::form.control-group class="w-full mb-2.5">
                                    <x-admin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('option::app.admin.catalog.options.create.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        :label="trans('option::app.admin.catalog.options.create.admin')"
                                        :placeholder="trans('option::app.admin.catalog.options.create.admin')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="admin_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Locales Input -->
                                @foreach ($allLocales as $locale)
                                    <x-admin::form.control-group class="w-full mb-2.5">
                                        <x-admin::form.control-group.label ::class="{ '{{core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }">
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            :name="$locale->code"
                                            ::rules="{ '{{core()->getDefaultLocaleCodeFromDefaultChannel() == $locale->code ? 'required' : ''}}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            :control-name="$locale->code"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                @endforeach
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <!-- Save Button -->
                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('option::app.admin.catalog.options.create.option.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>

            {!! view_render_event('bagisto.admin.options.create.after') !!}

        </script>

        <script type="module">
            app.component('v-create-attributes', {
                template: '#v-create-attributes-template',

                props: ['allLocales'],

                data() {
                    return {
                        optionRowCount: 1,

                        attributeType: '',

                        validationType: '',

                        inputValidation: false,

                        swatchType: '',

                        swatchAttribute: false,

                        showSwatch: false,

                        isNullOptionChecked: false,

                        options: [],
                    }
                },

                methods: {
                    storeOptions(params, { resetForm }) {
                        if (params.id) {
                            let foundIndex = this.options.findIndex(item => item.id === params.id);

                            this.options.splice(foundIndex, 1, {
                                ...this.options[foundIndex],
                                params: {
                                    ...this.options[foundIndex].params,
                                    ...params,
                                }
                            });
                        } else {
                            this.options.push({
                                id: 'option_' + this.optionRowCount++,
                                params
                            });
                        }

                        let formData = new FormData(this.$refs.createOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        params.swatch_value = sliderImage;

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(params);
                        }

                        resetForm();
                    },

                    editModal(values) {
                        values.params.id = values.id;

                        this.$refs.modelForm.setValues(values.params);

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        this.options = this.options.filter(option => option.id !== id);
                    },

                    listenModal(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;
                        }
                    },

                    setFile(event) {
                        let dataTransfer = new DataTransfer();

                        dataTransfer.items.add(event.swatch_value);

                        // use settimeout because need to wait for render dom before set the src or get the ref value
                        setTimeout(() => {
                            this.$refs['image_' + event.id].src =  URL.createObjectURL(event.swatch_value);
                        }, 0);

                        this.$refs['imageInput_' + event.id].files = dataTransfer.files;
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
