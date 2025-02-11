<v-mobile-category></v-mobile-category>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-category-template">
        <ul>
            <template v-for="(category) in categories">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.before') !!}

                <li class="dropdown drop-list relative leading-[28px]">
                    <span
                        class="menu-toggle h-[48px] absolute top-[0] right-[0] z-[-1] flex justify-center items-center cursor-pointer bg-transparent"
                        @click="toggle(category)"></span>
                    <a
                        :href="category.url"
                        class="dropdown-list py-[12px] block capitalize text-[15px] font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                        v-text="category.name"
                    >
                    </a>

                    <template v-if="category.isOpen">
                        <ul v-if="category.children.length" class="sub-menu w-full mb-[0] p-[0] hidden min-w-auto opacity-[1]">
                            <li v-for="secondLevelCategory in category.children">
                                <div class="flex justify-between items-center ltr:ml-3 rtl:mr-3 border border-b border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                    <a
                                        :href="secondLevelCategory.url"
                                        class="transition-all duration-[0.3s] ease-in-out pl-[20px] opacity-[0.8] text-[14px] py-[12px] block capitalize font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                                        v-text="secondLevelCategory.name"
                                    >
                                    </a>

                                    <span
                                        class="text-2xl cursor-pointer"
                                        :class="{
                                            'icon-arrow-down': secondLevelCategory.category_show,
                                            'icon-arrow-right': ! secondLevelCategory.category_show
                                        }"
                                        @click="secondLevelCategory.category_show = ! secondLevelCategory.category_show"
                                    >
                                    </span>
                                </div>

                                <div v-if="secondLevelCategory.category_show">
                                    <ul v-if="secondLevelCategory.children.length">
                                        <li v-for="thirdLevelCategory in secondLevelCategory.children">
                                            <div class="flex justify-between items-center ltr:ml-3 rtl:mr-3 border border-b border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                                <a
                                                    :href="thirdLevelCategory.url"
                                                    class="flex items-center justify-between mt-5 ltr:ml-3 rtl:mr-3 pb-5"
                                                    v-text="thirdLevelCategory.name"
                                                >
                                                </a>
                                            </div>
                                        </li>
                                    </ul>

                                    <span
                                        class="ltr:ml-2 rtl:mr-2"
                                        v-else
                                    >
                                        @lang('licious::app.components.layouts.header.no-category-found')
                                    </span>
                                </div>
                            </li>
                        </ul>

                        <span
                            class="ltr:ml-2 rtl:mr-2 mt-2"
                            v-else
                        >
                            @lang('licious::app.components.layouts.header.no-category-found')
                        </span>
                    </template>
                </li>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.after') !!}
            </template>
        </ul>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            inject: ['store'],

            data() {
                return  {
                    activeCategory: null,
                }
            },

            computed: {
                categories() {
                    return this.store.categories?.map((category) => ({
                        ...category,
                        isOpen: category.id === this.activeCategory?.id ? ! category.isOpen : false,
                    }));
                },
            },

            async mounted() {
                await this.store.getCategories("{{ route('shop.api.categories.tree') }}");
            },

            methods: {
                toggle(category) {
                    this.activeCategory = category;
                },
            },
        });
    </script>
@endPushOnce
