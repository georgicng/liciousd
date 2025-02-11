<v-desktop-category>
    <div class="flex gap-5 items-center">
        <span
            class="shimmer w-20 h-6 rounded"
            role="presentation"
        ></span>
        <span
            class="shimmer w-20 h-6 rounded"
            role="presentation"
        ></span>
        <span
            class="shimmer w-20 h-6 rounded"
            role="presentation"
        ></span>
    </div>
</v-desktop-category>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-category-template">
        <div
            class="flex gap-5 items-center"
            v-if="isLoading"
        >
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
            <span
                class="shimmer w-20 h-6 rounded"
                role="presentation"
            ></span>
        </div>

        <ul
            class="navbar-nav flex min-[992px]:flex-row items-center m-auto relative z-[3] min-[992px]:flex-row max-[1199px]:mr-[-5px] max-[991px]:m-[0]"
            v-else
        >
            <li
                class="nav-item dropdown relative mr-[25px] max-[1399px]:mr-[20px] max-[1199px]:mr-[30px]"
                v-for="category in categories"
            >
                <a
                    :href="category.url"
                    class="nav-link font-Poppins text-[14px] font-medium block text-[#000] z-[1] flex items-center relative py-[11px] px-[8px] max-[1199px]:py-[8px] max-[1199px]:px-[0]"
                    :class="{ 'dropdown-toggle': category.children.length }"
                    v-text="category.name"
                >
                </a>

                <ul
                    class="dropdown-menu transition-all duration-[0.3s] ease-in-out py-[8px] min-w-[160px] mt-[35px] absolute text-left opacity-0 invisible left-auto bg-[#fff] rounded-[5px] block z-[9] border-[1px] border-solid border-[#e9e9e9]"
                    v-if="category.children.length"
                >
                    <li
                        class="w-full mr-[0]"
                        v-for="pairCategoryChildren in pairCategoryChildren(category)"
                    >
                        <template v-for="secondLevelCategory in pairCategoryChildren">
                            <a
                                :href="secondLevelCategory.url"
                                class="dropdown-item transition-all duration-[0.3s] ease-in-out font-Poppins py-[7px] px-[20px] bg-[#fff] relative capitalize text-[13px] text-[#777] hover:text-[#64b496] whitespace-nowrap tracking-[0.03rem] block w-full"
                                v-text="secondLevelCategory.name"
                            >
                            </a>

                            <ul
                                class="grid grid-cols-[1fr] gap-3"
                                v-if="secondLevelCategory.children.length"
                            >
                                <li
                                    class="text-sm font-medium text-[#6E6E6E]"
                                    v-for="thirdLevelCategory in secondLevelCategory.children"
                                >
                                    <a
                                        :href="thirdLevelCategory.url"
                                        v-text="thirdLevelCategory.name"
                                    >
                                    </a>
                                </li>
                            </ul>
                        </template>
                    </li>
                </ul>
            </li>
        </ul>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',
            inject: ['store'],

            computed: {
                categories() {
                    return this.store.categories;
                },
                isLoading() {
                    return this.store.loading;
                }
            },

            mounted() {
                this.store.getCategories("{{ route('shop.api.categories.tree') }}")
            },


            methods: {
                pairCategoryChildren(category) {
                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }

                        return result;
                    }, []);
                }
            },
        });
    </script>
@endPushOnce
