<x-admin::layouts>
    <x-slot:title>
        @lang('pickup::app.admin.settings.menu.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.menu.create.before') !!}

        <v-menu> </v-menu>

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
                <div>
                    <!-- Attributes Groups Header -->
                    <div class="flex flex-col mb-4">
                        <p class="text-gray-600 dark:text-gray-300 font-semibold leading-6">
                            @lang('licious::app.admin.settings.menu.main-column')
                        </p>

                        <p class="text-xs text-gray-800 dark:text-white font-medium">
                            @lang('licious::app.admin.settings.menu.edit-group-info')
                        </p>
                    </div>

                    <!-- Draggable Attribute Groups -->
                    <v-nestable-draggable class="h-[calc(100vh-285px)] pb-4 overflow-auto ltr:border-r rtl:border-l border-gray-200" v-model="menus" :max-level="4" />
                </div>

                <!-- Unassigned Attributes Container -->
                <div class="">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('licious::app.admin.settings.menu.unassigned-options')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="categories" />
                        </x-slot:content>
                    </x-admin::accordion>
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('licious::app.admin.settings.menu.unassigned-options')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="pages" />
                        </x-slot:content>
                    </x-admin::accordion>
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="required p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                                @lang('licious::app.admin.settings.menu.unassigned-options')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <v-nestable-draggable v-model="custom" />
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
        >
            <template #item="{ element }">
                <div v-if="isFlat" class="flex gap-1.5 max-w-max py-1.5 ltr:pr-1.5 rtl:pl-1.5 rounded text-gray-600 dark:text-gray-300 group">
                    <i class="icon-drag text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white cursor-grab"></i>

                    <i class="text-xl transition-all group-hover:text-gray-800 dark:group-hover:text-white"></i>

                    <span
                        class="text-sm font-regular transition-all group-hover:text-gray-800 dark:group-hover:text-white max-xl:text-xs"
                        v-text="element.name"
                    >
                    </span>
                </div>
                <div
                    v-else
                    :class="[
                        'v-tree-item active inline-block w-full [&>.v-tree-item]:ltr:pl-6 [&>.v-tree-item]:rtl:pr-6 [&>.v-tree-item]:hidden [&.active>.v-tree-item]:block',
                        level === 1 && ! hasChildren(element)
                            ? 'ltr:!pl-5 rtl:!pr-5'
                            : level > 1 && ! hasChildren(element)
                            ? 'ltr:!pl-14 rtl:!pr-14'
                            : '',
                    ]">
                    <i :class="[element.children?.length ? 'icon-folder' : 'icon-attribute', 'text-2xl cursor-pointer' ]"> </i>
                    <div class="inline-flex gap-2.5 w-max p-1.5 items-center cursor-pointer select-none group">
                        <div
                            class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer hover:text-gray-800 dark:hover:text-white"
                            v-text="element.name"
                        ></div>
                    </div>
                    <v-nestable-draggable  :max-level="maxLevel" v-model="element.children" :level="level + 1" />
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
                }
            },
            mounted() {
                console.log(this.modelValue);
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>
