<v-mobile-category></v-mobile-category>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-category-template">
        <ul v-if="categories.length" class="cr-mobile-category-list">
            <template v-for="(category) in categories">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.before') !!}

                <li class="dropdown drop-list relative leading-[28px]">
                    <span class="flex justify-between items-center">
                        
                        <a
                            :href="category.url"
                            class="dropdown-list py-[12px] grow capitalize text-[15px] font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                            v-text="category.name"
                        >
                        </a>
                        <span v-if="category.children.length"
                            class="text-2xl items-center cursor-pointer bg-transparent"
                            :class="{
                                'ri-arrow-drop-up-line': activeCategory == category.id,
                                'ri-arrow-drop-right-line': activeCategory != category.id
                            }"
                            @click="toggle(category.id)"></span>
                    </span>

                    <template v-if="activeCategory == category.id">
                        <ul class="sub-menu w-full mb-[0] p-[0] min-w-auto opacity-[1]">
                            <li v-for="secondLevelCategory in category.children">
                                <div class="flex justify-between items-center ltr:ml-3 rtl:mr-3 border border-b border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                    <a
                                        :href="secondLevelCategory.url"
                                        class="transition-all duration-[0.3s] ease-in-out pl-[20px] opacity-[0.8] text-[14px] py-[12px] block capitalize font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                                        v-text="secondLevelCategory.name"
                                    >
                                    </a>

                                    <span
                                        v-if="secondLevelCategory.children.length"
                                        class="text-2xl cursor-pointer"
                                        :class="{
                                            'ri-arrow-drop-up-line': activeSubCategory == secondLevelCategory.id,
                                            'ri-arrow-drop-right-line': activeSubCategory != secondLevelCategory.id
                                        }"
                                        @click="toggleSecond(secondLevelCategory.id)"
                                    >
                                    </span>
                                </div>

                                <div v-if="activeSubCategory == secondLevelCategory.id">
                                    <ul>
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

                                </div>
                            </li>
                        </ul>                        
                    </template>
                </li>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.category.after') !!}
            </template>
           
        </ul>
         <span
            class="ltr:ml-2 rtl:mr-2 mt-2"
            v-else
        >
            @lang('licious::app.components.layouts.header.no-category-found')
        </span>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            inject: ['store'],

            data() {
                return  {
                    activeCategory: null,
                    activeSubCategory : null
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
                    this.activeCategory = this.activeCategory == category ? null : category;
                },
                toggleSecond(subCategory) {
                    this.activeSubCategory = this.activeSubCategory  == subCategory ? null : subCategory;
                },
            },
        });
    </script>
@endPushOnce
