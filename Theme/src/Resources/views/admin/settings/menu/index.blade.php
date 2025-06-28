<x-admin::layouts>
    <x-slot:title>
        @lang('licious::app.admin.settings.menu.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.menu.create.before') !!}
        <div class="flex justify-between items-center">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('licious::app.admin.settings.menu.index.page-title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.options.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('licious::app.admin.settings.menu.index.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('licious::app.admin.settings.menu.index.save-btn')
                </button>
            </div>
        </div>
        <div class="flex gap-2.5 mt-3.5">
            {{-- Left Container --}}
            <div class="flex flex-col gap-2 flex-1 bg-white dark:bg-gray-900 rounded box-shadow">
                <v-menu> </v-menu>
            </div>
            {{-- Right Container --}}
            <div class="flex flex-col gap-2 w-[360px] max-w-full">
            </div>
        </div>
    {!! view_render_event('bagisto.admin.settings.menu.create.after') !!}

    @pushOnce('scripts')
    <script type="text/x-template" id="v-menu-template">
        <div>
            <!-- Panel Header -->
            <div class="flex flex-wrap gap-2.5 justify-between mb-2.5 p-4">
                <!-- Panel Header -->
                <div class="flex flex-col gap-2">
                    <p class="text-base text-gray-800 dark:text-white font-semibold">
                        @lang('licious::app.admin.settings.menu.title')
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                        @lang('licious::app.admin.settings.menu.subtitle')
                    </p>
                </div>
            </div>

            <!-- Panel Content -->
            <div class="flex [&>*]:flex-1 gap-5 justify-between px-4">
                <!-- Attributes Groups Container -->
                <div class="w-2/3 overflow-auto ltr:border-r rtl:border-l border-gray-200">
                    <!-- Attributes Groups Header -->
                    <div class="flex flex-col mb-4">
                        <p class="text-gray-600 dark:text-gray-300 font-semibold leading-6">
                            @lang('licious::app.admin.settings.menu.menus')
                        </p>

                        <p class="text-xs text-gray-800 dark:text-white font-medium">
                            @lang('licious::app.admin.settings.menu.menus-description')
                        </p>
                    </div>

                    <!-- Draggable Attribute Groups -->
                    <v-nestable-draggable class="h-[calc(100vh-285px)] pb-4" v-model="menus" :max-level="4" :type="['custom', 'page', 'category']" />
                </div>

                <!-- Unassigned Attributes Container -->
                <div class="">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('licious::app.admin.settings.menu.categories')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="categories" type="category">
                                <template #default="{ element, level, isFlat, hasChildren }">
                                    <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group">
                                        <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>
                                        <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>
                                        <span
                                            class="text-sm font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs"
                                            v-text="element.name"
                                        >
                                        </span>
                                    </div>
                                </template>
                            </v-nestable-draggable>
                        </x-slot:content>
                    </x-admin::accordion>
                    <x-admin::accordion :is-active="false">
                        <x-slot:header>
                            <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('licious::app.admin.settings.menu.pages')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="pages" type="page">
                                <template #default="{ element, level, isFlat, hasChildren }">
                                    <div class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group">
                                        <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>
                                        <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>
                                        <span
                                            class="text-sm font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs"
                                            v-text="element.name"
                                        >
                                        </span>
                                    </div>
                                </template>
                            </v-nestable-draggable>
                        </x-slot:content>
                    </x-admin::accordion>
                    <x-admin::accordion :is-active="false">
                        <x-slot:header>
                            <div class="flex items-center justify-between w-full">
                                <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                    @lang('licious::app.admin.settings.menu.links')
                                </p>
                                <button
                                    type="button"
                                    class="secondary-button"
                                    @click="!isOpen && toggle(); addLink()"
                                >+</button>
                            </div>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="custom" type="custom">
                                <template #default="{ element, level, isFlat, hasChildren }">
                                    <v-collapse :is-active="true" class="mb-5 flex flex-col">
                                        <template v-slot:header>
                                            <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                                @{{ element.name }}
                                            </p>
                                        </template>
                                        <template v-slot:header_action>
                                                <div class="flex items-center">
                                                    <span class="icon-delete max-h-9 max-w-9 text-2xl p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center" @click="deleteLink(element)"></span>
                                                </div>
                                        </template>
                                        <template v-slot:content>
                                            <div>
                                                <div class="mb-4">
                                                    <label
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray bg-gray-100 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
                                                    >Name</label>
                                                    <input
                                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                        type="text"
                                                        v-model="element.name"
                                                        placeholder="Title"
                                                    />
                                                </div>
                                                <div>
                                                    <label
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray bg-gray-100 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
                                                    >Link</label>
                                                    <input
                                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                        type="text"
                                                        v-model="element.link"
                                                        placeholder="URL"
                                                    />
                                                </div>
                                            </div>
                                        </template>
                                    </v-collapse>
                                </template>
                            </v-nestable-draggable>
                        </x-slot:content>
                    </x-admin::accordion>
                </div>
            </div>
        </div>
    </script>
    <script type="module">
        app.component('v-menu', {
            template: '#v-menu-template',

            data: function() {
                return {
                    pages: @json($pages),
                    categories: @json($categories),
                    model: @json($data),
                    menus: [],
                    custom: []
                }
            },

            computed: {
                unassignedAttributes() {
                    return [];
                },
            },

            methods: {
                onMove: function(e) {
                },

                onEnd: function(e) {
                },

                addLink() {
                    this.custom.push({
                        key: Date.now(),
                        name: 'New Link',
                        url: '',
                        type: 'custom',
                        children: []
                    });
                },
                deleteLink(element) {
                    this.custom = this.custom.filter(item => item.key !== element.key);
                },
            },
            mounted() {
                this.menus = this.model.menus;
                this.custom = this.model.custom;
            },
            watch: {
                menus: {
                    handler(val) {
                        console.log('updated:modelValue', val);
                    },
                    deep: true
                }
            }
        });
    </script>
    <script type="text/x-template" id="v-nestable-draggable-template">
        <draggable
            class="pb-4"
            :class="{ 'v-tree-item-wrapper': !isFlat }"
            ghost-class="draggable-ghost"
            :list="model"
            item-key="key"
            :group="{ name: 'g1' }"
            :move="onMoveCallback"
        >
            <template #item="{ element }">
                <div>
                    <slot :element="element" :level="level" :isFlat="isFlat" :hasChildren="hasChildren">
                        <div
                            :class="[
                                'v-tree-item active inline-block w-full [&>.v-tree-item]:ltr:pl-6 [&>.v-tree-item]:rtl:pr-6 [&>.v-tree-item]:hidden [&.active>.v-tree-item]:block',
                                level !== 1
                                    ? 'ltr:!pl-5 rtl:!pr-5'
                                    : '',
                            ]">
                            <i :class="[element.children?.length ? 'icon-folder' : 'icon-attribute', 'text-2xl cursor-pointer' ]"> </i>
                            <div class="inline-flex gap-2.5 w-max p-1.5 items-center cursor-pointer select-none group">
                                <div
                                    class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:text-gray-800 dark:hover:text-white"
                                    v-text="element.name"
                                ></div>
                            </div>
                            <v-nestable-draggable  :max-level="maxLevel" v-model="element.children" :level="level + 1" :type="type" />
                        </div>
                    </slot>
                </div>
            </template>
        </draggable>
    </script>
    <script type="module">
        app.component('v-nestable-draggable', {
            template: '#v-nestable-draggable-template',
            props: {
                modelValue: {
                    required: true,
                    type: Array
                },
                maxLevel: {
                    default: 1,
                    type: Number
                },
                level: {
                    default: 1,
                    type: Number
                },
                type: {
                    default: '',
                    type: [String, Array]
                },
            },
            data() {
                return {
                    model_: this.modelValue
                }
            },
            computed: {
                isFlat() {
                    return this.maxLevel == this.level && this.level == 1;
                },
                isRoot() {
                    return this.level == 1;
                },
                model: {
                    get() {
                        return this.modelValue
                    },
                    set(val) {
                        console.log(val)
                        this.$emit('update:modelValue', [...val]);
                    }
                }
            },
            methods: {
                hasChildren(item) {
                    return !!item?.children?.length;
                },
                onMoveCallback(evt) {
                    if (!this.type) {
                        return true;
                    }
                    if (Array.isArray(this.type)) {
                        return this.type.includes(evt.draggedContext.element.type);
                    }
                    return (evt.draggedContext.element.type == this.type);
                }
            },
            mounted() {
                console.log(this.modelValue);
            }
        });
    </script>

    <script type="text/x-template" id="v-collapse-template">
        <div class="bg-white dark:bg-gray-900 rounded box-shadow">
            <div :class="`flex items-center justify-between p-1.5 ${isOpen ? 'active' : ''}`">
                <div class="flex items-center" @click="toggle">
                    <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>
                    <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                    <slot name="header">
                        Default Header
                    </slot>
                </div>
                <slot name="header_action">
                    Close button
                </slot>
            </div>

            <div class="px-4 pb-4" v-show="isOpen">
                <slot name="content">
                    Default Content
                </slot>
            </div>
        </div>
    </script>
    <script type="module">
        app.component('v-collapse', {
            template: '#v-collapse-template',

            props: [
                'isActive',
            ],

            data() {
                return {
                    isOpen: this.isActive,
                };
            },

            methods: {
                toggle() {
                    this.isOpen = !this.isOpen;

                    this.$emit('toggle', {
                        isActive: this.isOpen
                    });
                },
            },
        });
    </script>
    @endPushOnce
</x-admin::layouts>
