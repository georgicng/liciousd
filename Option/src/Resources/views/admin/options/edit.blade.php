@php
    $allLocales = app('Webkul\Core\Repositories\LocaleRepository')->all();
@endphp

<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('option::app.admin.catalog.options.edit.title')
    </x-slot:title>

    {{-- Edit Attributes Vue Components --}}
    <v-edit-attributes :all-locales="{{ $allLocales->toJson() }}"></v-edit-attributes>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-edit-attributes-template"
        >

            {!! view_render_event('bagisto.admin.catalog.options.edit.before') !!}

            <!-- Input Form -->
            <x-admin::form
                :action="route('admin.options.update', $option->id)"
                enctype="multipart/form-data"
                method="PUT"
            >
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('option::app.admin.catalog.options.edit.title')
                    </p>

                    <div class="flex gap-x-[10px] items-center">
                        <!-- Cancel Button -->
                        <a
                            href="{{ route('admin.catalog.attributes.index') }}"
                            class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white "
                        >
                            @lang('option::app.admin.catalog.options.edit.back-btn')
                        </a>

                        <!-- Save Button -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('option::app.admin.catalog.options.edit.save-btn')
                        </button>
                    </div>
                </div>

                <!-- body content -->
                <div class="flex gap-[10px] mt-[14px]">
                    <!-- Left sub Component -->
                    <div class="flex flex-col flex-1 gap-[8px] overflow-auto">

                        {!! view_render_event('bagisto.admin.catalog.options.edit.card.label.before', ['option' => $option]) !!}

                        <!-- Label -->
                        <div class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                @lang('option::app.admin.catalog.options.edit.label')
                            </p>

                            <!-- Admin name -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('option::app.admin.catalog.options.edit.admin')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="admin_name"
                                    :value="old('admin_name') ?: $option->admin_name"
                                    rules="required"
                                    :label="trans('option::app.admin.catalog.options.edit.admin')"
                                    :placeholder="trans('option::app.admin.catalog.options.edit.admin')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="admin_name"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <!-- Locales Inputs -->
                            @foreach ($allLocales as $locale)
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        {{ $locale->name . ' (' . strtoupper($locale->code) . ')' }}
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        :name="$locale->code . '[name]'"
                                        :value="old($locale->code)['name'] ?? ($option->translate($locale->code)->name ?? '')"
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

                        {!! view_render_event('bagisto.admin.catalog.options.edit.card.label.after', ['option' => $option]) !!}

                        <!-- Options -->
                        <div
                            class="p-[16px] bg-white dark:bg-gray-900 box-shadow rounded-[4px] {{ in_array($option->type, ['select', 'multiselect', 'checkbox', 'price']) ?: 'hidden' }}"
                            v-if="showSwatch"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <p class="mb-[16px] text-[16px] text-gray-800 dark:text-white font-semibold">
                                    @lang('option::app.admin.catalog.options.edit.title')
                                </p>

                                <!-- Add Row Button -->
                                <div
                                    class="secondary-button text-[14px]"
                                    @click="$refs.addOptionsRow.toggle()"
                                >
                                    @lang('option::app.admin.catalog.options.edit.add-row')
                                </div>
                            </div>

                            <!-- For Attribute Options If Data Exist -->
                            @if (
                                $option->type == 'select'
                                || $option->type == 'multiselect'
                                || $option->type == 'checkbox'
                                || $option->type == 'price'
                            )
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <!-- Input Options -->
                                    <x-admin::form.control-group
                                        class="w-full mb-[10px]"
                                        v-if="this.showSwatch"
                                    >
                                        <x-admin::form.control-group.label for="swatchType">
                                            @lang('option::app.admin.catalog.options.edit.input-options')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="swatch_type"
                                            id="swatchType"
                                            v-model="swatchType"
                                            @change="showSwatch=true"
                                        >
                                            @foreach (['dropdown', 'color', 'image', 'text'] as $type)
                                                <option value="{{ $type }}">
                                                    @lang('option::app.admin.catalog.options.edit.option.' . $type)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="admin"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <div class="w-full mb-[10px]">
                                        <!-- checkbox -->
                                        <x-admin::form.control-group.label class="invisible">
                                            @lang('option::app.admin.catalog.options.edit.input-options')
                                        </x-admin::form.control-group.label>

                                        <div class="flex gap-[10px] w-max !mb-0 p-[6px] cursor-pointer select-none">
                                            <input
                                                type="checkbox"
                                                name="empty_option"
                                                id="empty_option"
                                                for="empty_option"
                                                class="hidden peer"
                                                v-model="isNullOptionChecked"
                                                @click="$refs.addOptionsRow.toggle()"
                                            >

                                            <label
                                                for="empty_option"
                                                class="icon-uncheckbox text-[24px] rounded-[6px] cursor-pointer peer-checked:icon-checked peer-checked:text-blue-600 "
                                            >
                                            </label>

                                            <label
                                                for="empty_option"
                                                class="text-[14px] text-gray-600 dark:text-gray-300 font-semibold cursor-pointer"
                                            >
                                                @lang('option::app.admin.catalog.options.edit.create-empty-option')
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Information -->
                                <div class="mt-[15px] overflow-x-auto">
                                    <x-admin::table>
                                        <x-admin::table.thead class="text-[14px] font-medium dark:bg-gray-800">
                                            <x-admin::table.thead.tr>
                                                <x-admin::table.th class="!p-0"></x-admin::table.th>

                                                <!-- Swatch Select -->
                                                <x-admin::table.th v-if="showSwatch && (swatchType == 'color' || swatchType == 'image')">
                                                    @lang('option::app.admin.catalog.options.edit.swatch')
                                                </x-admin::table.th>

                                                <!-- Admin tables heading -->
                                                <x-admin::table.th>
                                                    @lang('option::app.admin.catalog.options.edit.admin-name')
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
                                            :list="optionsData"
                                            item-key="id"
                                        >
                                            <template #item="{ element, index }" v-show="! element.isDelete">
                                                <x-admin::table.thead.tr class="text-center hover:bg-gray-50 dark:hover:bg-gray-950">
                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][isNew]'"
                                                        :value="element.isNew"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        :name="'options[' + element.id + '][isDelete]'"
                                                        :value="element.isDelete"
                                                    >

                                                    <!-- Draggable Icon -->
                                                    <x-admin::table.td class="!px-0">
                                                        <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

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
                                                                :src="element.swatch_value_url"
                                                                :ref="'image_' + element.id"
                                                                class="h-[50px] w-[50px]"
                                                            >

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
                                                                class="w-[25px] h-[25px] mx-auto rounded-[5px]"
                                                                :style="{ background: element.swatch_value }"
                                                            >
                                                            </div>

                                                            <input
                                                                type="hidden"
                                                                :name="'options[' + element.id + '][swatch_value]'"
                                                                v-model="element.swatch_value"
                                                            />
                                                        </div>
                                                    </x-admin::table.td>

                                                    <!-- Admin-->
                                                    <x-admin::table.td>
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element.admin_name"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][admin_name]'"
                                                            v-model="element.admin_name"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Loacles -->
                                                     <x-admin::table.td v-for="locale in allLocales">
                                                        <p
                                                            class="dark:text-white"
                                                            v-text="element['locales'][locale.code]"
                                                        >
                                                        </p>

                                                        <input
                                                            type="hidden"
                                                            :name="'options[' + element.id + '][' + locale.code + '][label]'"
                                                            v-model="element['locales'][locale.code]"
                                                        />
                                                    </x-admin::table.td>

                                                    <!-- Actions Button -->
                                                    <x-admin::table.td class="!px-0">
                                                        <span
                                                            class="icon-edit p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                            @click="editOptions(element)"
                                                        >
                                                        </span>

                                                        <span
                                                            class="icon-delete p-[6px] rounded-[6px] text-[24px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-800  max-sm:place-self-center"
                                                            @click="removeOption(element.id)"
                                                        >
                                                        </span>
                                                    </x-admin::table.td>
                                                </x-admin::table.thead.tr>
                                            </template>
                                        </draggable>
                                    </x-admin::table>
                                </div>
                            @else
                                <!-- For Empty Attribute Options -->
                                <template>
                                    <div class="grid gap-[14px] justify-items-center py-[40px] px-[10px]">
                                        <!-- Attribute Option Image -->
                                        <img
                                            class="w-[120px] h-[120px] border border-dashed border-gray-300 dark:border-gray-800 rounded-[4px]"
                                            src="{{ bagisto_asset('images/icon-add-product.svg') }}"
                                            alt="{{ trans('option::app.admin.catalog.options.edit.add-attribute-options') }}"
                                        >

                                        <!-- Add Attribute Options Information -->
                                        <div class="flex flex-col items-center">
                                            <p class="text-[16px] text-gray-400 font-semibold">
                                                @lang('option::app.admin.catalog.options.edit.add-attribute-options')
                                            </p>

                                            <p class="text-gray-400">
                                                @lang('option::app.admin.catalog.options.edit.add-options-info')
                                            </p>
                                        </div>

                                        <!-- Add Row Button -->
                                        <div
                                            class="secondary-button text-[14px]"
                                            @click="$refs.addOptionsRow.toggle()"
                                        >
                                            @lang('option::app.admin.catalog.options.edit.add-row')
                                        </div>
                                    </div>
                                </template>
                            @endif
                        </div>
                    </div>

                    <!-- Right sub-component -->
                    <div class="flex flex-col gap-[8px] w-[360px] max-w-full">

                        {!! view_render_event('bagisto.admin.catalog.options.edit.card.accordian.general.before', ['option' => $option]) !!}

                        <!-- General -->
                        <div class="bg-white dark:bg-gray-900 box-shadow rounded-[4px]">
                            <div class="flex justify-between items-center p-[6px]">
                                <p class="p-[10px] text-gray-800 dark:text-white text-[16px] font-semibold">
                                    @lang('option::app.admin.catalog.options.edit.general')
                                </p>
                            </div>

                            <div class="px-[16px] pb-[16px]">
                                <!-- Attribute Code -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('option::app.admin.catalog.options.edit.code')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedOption = old('type') ?: $option->code;
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="$selectedOption"
                                        class="cursor-not-allowed"
                                        rules="required"
                                        :disabled="(boolean) $selectedOption"
                                        readonly
                                        :label="trans('option::app.admin.catalog.options.edit.code')"
                                        :placeholder="trans('option::app.admin.catalog.options.edit.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="code"
                                        :value="$selectedOption"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Attribute Type -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('option::app.admin.catalog.options.edit.type')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedOption = old('type') ?: $option->type;
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        id="type"
                                        class="cursor-not-allowed"
                                        :value="$selectedOption"
                                        :disabled="(boolean) $selectedOption"
                                        :label="trans('option::app.admin.catalog.options.edit.type')"
                                    >
                                        <!-- Here! All Needed types are defined -->
                                        @foreach(['text', 'textarea', 'boolean', 'select', 'multiselect', 'file'] as $type)
                                            <option
                                                value="{{ $type }}"
                                                {{ $selectedOption == $type ? 'selected' : '' }}
                                            >
                                                @lang('option::app.admin.catalog.options.edit.'. $type)
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.control
                                        type="hidden"
                                        name="type"
                                        :value="$option->type"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="type"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.catalog.options.edit.card.accordian.general.after', ['option' => $option]) !!}

                    </div>
                </div>
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
                    ref="editOptionsForm"
                >
                    <x-admin::modal
                        @toggle="listenModel"
                        ref="addOptionsRow"
                    >
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('option::app.admin.catalog.options.edit.add-option')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="grid grid-cols-3 px-[16px] py-[10px]">
                                <!-- Image Input -->
                                <x-admin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'image'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('option::app.admin.catalog.options.edit.image')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="swatch_value"
                                        :placeholder="trans('option::app.admin.catalog.options.edit.image')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Color Input -->
                                <x-admin::form.control-group
                                    class="w-full"
                                    v-if="swatchType == 'color'"
                                >
                                    <x-admin::form.control-group.label>
                                        @lang('option::app.admin.catalog.options.edit.color')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="color"
                                        name="swatch_value[]"
                                        :placeholder="trans('option::app.admin.catalog.options.edit.color')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="swatch_value[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-3 gap-[16px] px-[16px] py-[10px] border-b-[1px] dark:border-gray-800  ">
                                <!-- Hidden Id Input -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="isNew"
                                    ::value="optionIsNew"
                                >
                                </x-admin::form.control-group.control>

                                <!-- Admin Input -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label ::class="{ 'required' : ! isNullOptionChecked }">
                                        @lang('option::app.admin.catalog.options.edit.admin')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="admin_name"
                                        ::rules="{ 'required' : ! isNullOptionChecked }"
                                        ref="inputAdmin"
                                        :label="trans('option::app.admin.catalog.options.edit.admin')"
                                        :placeholder="trans('option::app.admin.catalog.options.edit.admin')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="admin_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Locales Input -->
                                @foreach ($allLocales as $locale)
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label ::class="{ '{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }">
                                            {{ $locale->name }} ({{ strtoupper($locale->code) }})
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="locales.{{ $locale->code }}"
                                            ::rules="{ '{{ core()->getDefaultChannelLocaleCode() == $locale->code ? 'required' : '' }}' : ! isNullOptionChecked }"
                                            :label="$locale->name"
                                            :placeholder="$locale->name"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="locales.{{ $locale->code }}"
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
                                @lang('option::app.admin.catalog.options.edit.option.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>

            {!! view_render_event('bagisto.admin.catalog.options.edit.after') !!}

        </script>

        <script type="module">
            app.component('v-edit-attributes', {
                template: '#v-edit-attributes-template',

                props: ['allLocales'],

                data: function() {
                    return {
                        optionRowCount: 1,

                        showSwatch: {{ in_array($option->type, ['select', 'checkbox', 'price', 'multiselect']) ? 'true' : 'false' }},

                        isNullOptionChecked: false,

                        optionsData: [],

                        optionIsNew: true,

                        optionId: 0,

                        src: "{{ route('admin.options.values', $option->id) }}",
                    }
                },

                created: function () {
                    this.getAttributesOption();
                },

                methods: {
                    storeOptions(params, { resetForm, setValues }) {
                        if (! params.id) {
                            params.id = 'option_' + this.optionId;
                            this.optionId++;
                        }

                        let foundIndex = this.optionsData.findIndex(item => item.id === params.id);

                        if (foundIndex !== -1) {
                            this.optionsData.splice(foundIndex, 1, params);
                        } else {
                            this.optionsData.push(params);
                        }

                        let formData = new FormData(this.$refs.editOptionsForm);

                        const sliderImage = formData.get("swatch_value[]");

                        params.swatch_value = sliderImage;

                        this.$refs.addOptionsRow.toggle();

                        if (params.swatch_value instanceof File) {
                            this.setFile(params);
                        }

                        resetForm();
                    },

                    editOptions(value) {
                        this.optionIsNew = false;

                        this.$refs.modelForm.setValues(value);

                        this.$refs.addOptionsRow.toggle();
                    },

                    removeOption(id) {
                        let foundIndex = this.optionsData.findIndex(item => item.id === id);

                        if (foundIndex !== -1) {
                            this.optionsData.splice(foundIndex, 1);
                        }
                    },

                    listenModel(event) {
                        if (! event.isActive) {
                            this.isNullOptionChecked = false;
                        }
                    },

                    getAttributesOption() {
                        this.$axios.get(`${this.src}`)
                            .then(response => {
                                let options = response.data;
                                options.forEach((option) => {
                                    this.optionRowCount++;

                                    let row = {
                                        'id': option.id,
                                        'admin_name': option.admin_name,
                                        'sort_order': option.sort_order,
                                        'swatch_value': option.swatch_value,
                                        'swatch_value_url': option.swatch_value_url,
                                        'notRequired': '',
                                        'locales': {},
                                        'isNew': false,
                                        'isDelete': false,
                                    };

                                    if (! option.label) {
                                        this.isNullOptionChecked = true;
                                        this.idNullOption = option.id;
                                        row['notRequired'] = true;
                                    } else {
                                        row['notRequired'] = false;
                                    }

                                    option.translations.forEach((translation) => {
                                        row['locales'][translation.locale] = translation.label ?? '';
                                    });

                                    this.optionsData.push(row);
                                });
                            });
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
