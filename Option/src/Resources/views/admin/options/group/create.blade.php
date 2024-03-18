@inject('optionGroupRepository','Gaiproject\Option\Repositories\OptionGroupRepository')
@inject('optionRepository', 'Gaiproject\Option\Repositories\OptionRepository')

@php
$groups = $optionGroupRepository->getByFamily()->groupBy('column');
$customOptions = $optionRepository->all(['id', 'code', 'admin_name', 'type']);
@endphp
<div class="flex gap-[10px] mt-[14px]">
    {{-- Left Container --}}
    <div class="flex flex-col gap-[8px] flex-1 bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
        <v-family-options>
            <x-admin::shimmer.families.attributes-panel />
        </v-family-options>
    </div>
    {{-- Right Container --}}
    <div class="flex flex-col gap-[8px] w-[360px] max-w-full">
    </div>
</div>

@pushOnce('scripts')
<script type="text/x-template" id="v-family-options-template">
    <div>
                <!-- Panel Header -->
                <div class="flex flex-wrap gap-[10px] justify-between mb-[10px] p-[16px]">
                    <!-- Panel Header -->
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            @lang('option::app.admin.catalog.families.create.groups')
                        </p>

                        <p class="text-[12px] text-gray-500 dark:text-gray-300 font-medium">
                            @lang('option::app.admin.catalog.families.create.groups-info')
                        </p>
                    </div>

                    <!-- Panel Content -->
                    <div class="flex gap-x-[4px] items-center">
                        <!-- Delete Group Button -->
                        <div
                            class="px-[12px] py-[5px] border-[2px] border-transparent rounded-[6px] text-red-600 font-semibold whitespace-nowrap transition-all hover:bg-gray-100 dark:hover:bg-gray-950 cursor-pointer"
                            @click="deleteGroup"
                        >
                            @lang('option::app.admin.catalog.families.create.delete-group-btn')
                        </div>

                        <!-- Add Group Button -->
                        <div
                            class="secondary-button"
                            @click="$refs.addGroupModal.open()"
                        >
                            @lang('option::app.admin.catalog.families.create.add-group-btn')
                        </div>
                    </div>
                </div>

                <!-- Panel Content -->
                <div class="flex [&>*]:flex-1 gap-[20px] justify-between px-[16px]">
                    <!-- Attributes Groups Container -->
                    <div v-for="(groups, column) in columnGroups">
                        <!-- Attributes Groups Header -->
                        <div class="flex flex-col mb-[16px]">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold leading-[24px]">
                                @{{
                                    column == 1
                                    ? "@lang('option::app.admin.catalog.families.create.main-column')"
                                    : "@lang('option::app.admin.catalog.families.create.right-column')"
                                }}
                            </p>

                            <p class="text-[12px] text-gray-800 dark:text-white font-medium">
                                @lang('option::app.admin.catalog.families.create.edit-group-info')
                            </p>
                        </div>

                        <!-- Draggable Attribute Groups -->
                        <draggable
                            class="h-[calc(100vh-285px)] pb-[16px] overflow-auto ltr:border-r-[1px] rtl:border-l-[1px] border-gray-200"
                            ghost-class="draggable-ghost"
                            v-bind="{animation: 200}"
                            :list="groups"
                            item-key="id"
                            group="groups"
                        >
                            <template #item="{ element, index }">
                                <div class="">
                                    <!-- Group Container -->
                                    <div class="flex items-center group">
                                        <!-- Toggle -->
                                        <i
                                            class="icon-sort-down text-[20px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 group-hover:text-gray-800"
                                            @click="element.hide = ! element.hide"
                                        >
                                        </i>

                                        <!-- Group Name -->
                                        <div
                                            class="group_node flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px] rounded-[4px] text-gray-600 dark:text-gray-300 group cursor-pointer transition-all group-hover:text-gray-800"
                                            :class="{'bg-blue-600 text-white group-hover:[&>*]:text-white': selectedGroup.id == element.id}"
                                            @click="groupSelected(element)"
                                        >
                                            <i class="icon-drag text-[20px] text-inherit pointer-events-none transition-all group-hover:text-gray-800"></i>

                                            <i
                                                class="text-[20px] text-inherit pointer-events-none transition-all group-hover:text-gray-800"
                                                :class="[element.is_user_defined ? 'icon-folder' : 'icon-folder-block']"
                                            >
                                            </i>

                                            <span
                                                class="text-[14px] text-inherit font-regular pointer-events-none transition-all group-hover:text-gray-800"
                                                v-show="editableGroup.id != element.id"
                                                v-text="element.name"
                                            >
                                            </span>

                                            <input
                                                type="text"
                                                :name="'option_groups[' + element.id + '][name]'"
                                                class="group_node text-[14px] !text-gray-600 dark:!text-gray-300"
                                                v-model="element.name"
                                                v-show="editableGroup.id == element.id"
                                            />

                                            <input
                                                type="hidden"
                                                :name="'option_groups[' + element.id + '][position]'"
                                                :value="index + 1"
                                            />

                                            <input
                                                type="hidden"
                                                :name="'option_groups[' + element.id + '][column]'"
                                                :value="column"
                                            />
                                        </div>
                                    </div>

                                    <!-- Group Attributes -->
                                    <draggable
                                        class="ltr:ml-[43px] rtl:mr-[43px]"
                                        ghost-class="draggable-ghost"
                                        v-bind="{animation: 200}"
                                        :list="getGroupAttributes(element)"
                                        item-key="id"
                                        group="attributes"
                                        :move="onMove"
                                        @end="onEnd"
                                        v-show="! element.hide"
                                    >
                                        <template #item="{ element, index }">
                                            <div class="flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px] rounded-[4px] text-gray-600 dark:text-gray-300 group cursor-pointer">
                                                <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                                <i
                                                    class="text-[20px] transition-all group-hover:text-gray-700"
                                                    :class="[element.is_user_defined ? 'icon-attribute' : 'icon-attribute-block']"
                                                >
                                                </i>


                                                <span
                                                    class="text-[14px] font-regular transition-all group-hover:text-gray-800 max-xl:text-[12px]"
                                                    v-text="element.admin_name"
                                                >
                                                </span>

                                                <input
                                                    type="hidden"
                                                    :name="'option_groups[' + element.group_id + '][custom_options][' + index + '][id]'"
                                                    class="text-[14px] text-gray-600 dark:text-gray-300"
                                                    v-model="element.id"
                                                />

                                                <input
                                                    type="hidden"
                                                    :name="'option_groups[' + element.group_id + '][custom_options][' + index + '][position]'"
                                                    class="text-[14px] text-gray-600 dark:text-gray-300"
                                                    :value="index + 1"
                                                />
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </template>
                        </draggable>
                    </div>

                    <!-- Unassigned Attributes Container -->
                    <div class="">
                        <!-- Unassigned Attributes Header -->
                        <div class="flex flex-col mb-[16px]">
                            <p class="text-gray-600 dark:text-gray-300  font-semibold leading-[24px]">
                                @lang('option::app.admin.catalog.families.create.unassigned-attributes')
                            </p>

                            <p class="text-[12px] text-gray-800 dark:text-white font-medium ">
                                @lang('option::app.admin.catalog.families.create.unassigned-attributes-info')
                            </p>
                        </div>

                        <!-- Draggable Unassigned Attributes -->
                        <draggable
                            id="unassigned-attributes"
                            class="h-[calc(100vh-285px)] pb-[16px] overflow-auto"
                            ghost-class="draggable-ghost"
                            v-bind="{animation: 200}"
                            :list="unassignedAttributes"
                            item-key="id"
                            group="attributes"
                        >
                            <template #item="{ element }">
                                <div class="flex gap-[6px] max-w-max py-[6px] ltr:pr-[6px] rtl:pl-[6px] rounded-[4px] text-gray-600 dark:text-gray-300 group cursor-pointer">
                                    <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i>

                                    <i class="text-[20px] transition-all group-hover:text-gray-700"></i>

                                    <span
                                        class="text-[14px] font-regular transition-all group-hover:text-gray-800 max-xl:text-[12px]"
                                        v-text="element.admin_name"
                                    >
                                    </span>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, addGroup)">
                        <!-- Model Form -->
                        <x-admin::modal ref="addGroupModal">
                            <!-- Model Header -->
                            <x-slot:header>
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    @lang('option::app.admin.catalog.families.create.add-group-title')
                                </p>
                            </x-slot:header>

                            <!--Model Content -->
                            <x-slot:content>
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <!-- Group Name -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('option::app.admin.catalog.families.create.name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="name"
                                            rules="required"
                                            :label="trans('option::app.admin.catalog.families.create.name')"
                                            :placeholder="trans('option::app.admin.catalog.families.create.name')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="name"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Select Group Type -->
                                    <x-admin::form.control-group class="mb-4">
                                        <x-admin::form.control-group.label class="required !text-gray-800 font-medium">
                                            @lang('option::app.admin.catalog.families.create.column')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="column"
                                            rules="required"
                                            :label="trans('option::app.admin.catalog.families.create.column')"
                                        >
                                            <!-- Default Option -->
                                            <option value="">
                                                @lang('option::app.admin.catalog.families.create.select-group')
                                            </option>

                                            <option value="1">
                                                @lang('option::app.admin.catalog.families.create.main-column')
                                            </option>

                                            <option value="2">
                                                @lang('option::app.admin.catalog.families.create.right-column')
                                            </option>
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error control-name="column"></x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>

                            <!-- Model Footer -->
                            <x-slot:footer>
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Add Group Button -->
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('option::app.admin.catalog.families.create.add-group-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>
<script type="module">
    app.component('v-family-options', {
        template: '#v-family-options-template',

        data: function() {
            return {
                selectedGroup: {
                    id: null,
                    name: null,
                },

                editableGroup: {
                    id: null,
                    name: null,
                },

                columnGroups: @json($groups),

                customAttributes: @json($customOptions),

                dropReverted: false,
            }
        },

        created() {
            window.addEventListener('click', this.handleFocusOut);
        },

        computed: {
            unassignedAttributes() {
                return this.customAttributes.filter(attribute => {
                    return !this.columnGroups[1].find(group => group.custom_options.find(customAttribute => customAttribute.id == attribute.id)) &&
                        !this.columnGroups[2]?.find(group => group.custom_options.find(customAttribute => customAttribute.id == attribute.id));
                });
            },
        },

        methods: {
            onMove: function(e) {
                if (
                    e.to.id === 'unassigned-attributes' &&
                    !e.draggedContext.element.is_user_defined
                ) {
                    this.dropReverted = true;

                    return false;
                } else {
                    this.dropReverted = false;
                }
            },

            onEnd: function(e) {
                if (this.dropReverted) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: "@lang('option::app.admin.catalog.families.create.removal-not-possible')"
                    });
                }
            },

            getGroupAttributes(group) {
                group.custom_options.forEach((attribute, index) => {
                    attribute.group_id = group.id;
                });

                return group.custom_options;
            },

            groupSelected(group) {
                if (this.selectedGroup.id) {
                    this.editableGroup = this.selectedGroup.id == group.id ?
                        group : {
                            id: null,
                            name: null,
                        };
                }

                this.selectedGroup = group;
            },

            addGroup(params, {
                resetForm,
                setErrors
            }) {
                if (this.isGroupAlreadyExists(params.name)) {
                    setErrors({
                        'name': ["@lang('option::app.admin.catalog.families.create.group-already-exists')"]
                    });

                    return;
                }

                if (!this.columnGroups.hasOwnProperty(params.column)) {
                    this.columnGroups[params.column] = [];
                }

                this.columnGroups[params.column].push({
                    'id': 'group_' + params.column + '_' + this.columnGroups[params.column].length,
                    'name': params.name,
                    'is_user_defined': 1,
                    'custom_options': [],
                });

                resetForm();

                this.$refs.addGroupModal.close();
            },

            isGroupAlreadyExists(name) {
                return this.columnGroups[1].find(group => group.name == name) || this.columnGroups[2]?.find(group => group.name == name);
            },

            isGroupContainsSystemAttributes(group) {
                return group.custom_options.find(attribute => !attribute.is_user_defined);
            },

            deleteGroup() {
                if (!this.selectedGroup.id) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: "@lang('option::app.admin.catalog.families.create.select-group')"
                    });

                    return;
                }

                if (this.isGroupContainsSystemAttributes(this.selectedGroup)) {
                    this.$emitter.emit('add-flash', {
                        type: 'warning',
                        message: "@lang('option::app.admin.catalog.families.create.group-contains-system-attributes')"
                    });

                    return;
                }

                for (const [key, groups] of Object.entries(this.columnGroups)) {
                    let index = groups.indexOf(this.selectedGroup);

                    if (index > -1) {
                        groups.splice(index, 1);
                    }
                }
            },

            handleFocusOut(e) {
                if (!e.target.classList.contains('group_node')) {
                    this.editableGroup = {
                        id: null,
                        name: null,
                    };
                }
            },
        }
    });
</script>
@endPushOnce
