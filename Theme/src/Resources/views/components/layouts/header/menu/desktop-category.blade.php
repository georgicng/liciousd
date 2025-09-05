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
            class="navbar-nav flex min-[992px]:flex-row items-center m-auto relative z-[3] max-[1199px]:mr-[-5px] max-[991px]:m-[0]"
            v-else
        >
            <li
                class="nav-item dropdown mr-[25px] max-[1399px]:mr-[20px] max-[1199px]:mr-[30px]"
                :class="{ 'relative': !isMegaMenu(menu) }"
                v-for="menu in menus"
            >
                <a
                    :href="menu.url"
                    class="nav-link font-Poppins text-[14px] font-medium text-[#000] z-[1] flex items-center relative py-[11px] px-[8px] max-[1199px]:py-[8px] max-[1199px]:px-[0]"
                    :class="{ 'dropdown-toggle': menu.children.length }"
                    v-text="menu.name"
                >
                </a>
                <div
                    class="dropdown-menu transition-all duration-[0.3s] ease-in-out py-[8px] min-w-[160px] mt-[35px] absolute text-left opacity-0 invisible  bg-[#fff] rounded-[5px] block z-[9] border-[1px] border-solid border-[#e9e9e9]"
                    :class="[!isMegaMenu(menu) ? 'left-auto': 'left-0 right-0 min-h-48']"
                    v-if="menu?.submenus"
                >
                    <div class="flex">
                        <ul v-if="menu.submenus?.flat.length">
                            <li
                                class="w-full mr-[0]"
                                v-for="secondLevelMenu in menu.submenus.flat"
                            >
                                <a
                                    :href="secondLevelMenu.url"
                                    class="dropdown-item transition-all duration-[0.3s] ease-in-out font-Poppins py-[7px] px-[20px] bg-[#fff] relative capitalize font-medium text-[13px] text-[#777] hover:text-[#64b496] whitespace-nowrap tracking-[0.03rem] block w-full"
                                    v-text="secondLevelMenu.name"
                                >
                                </a>
                            </li>
                        </ul>
                        <template v-if="menu.submenus?.nested.length">
                            <ul v-for="secondLevelMenu in menu.submenus.nested">
                                <li
                                    class="w-full mr-[0]"
                                >
                                    <a
                                        :href="secondLevelMenu.url"
                                        class="dropdown-item transition-all duration-[0.3s] ease-in-out font-Poppins py-[7px] px-[20px] bg-[#fff] relative capitalize font-medium text-[13px] text-[#777] bold hover:text-[#64b496] whitespace-nowrap tracking-[0.03rem] block w-full"
                                        v-text="secondLevelMenu.name"
                                    >
                                    </a>

                                    <ul
                                        class="pl-6 grid grid-cols-[1fr] px-[20px]"
                                        v-if="secondLevelMenu.children.length"
                                    >
                                        <li
                                            class="w-full mr-[0] flex items-center"
                                            v-for="thirdLevelMenu in secondLevelMenu.children"
                                        >
                                            <span class="py-[7px]"> - </span>
                                            <a
                                                :href="thirdLevelMenu.url"
                                                class="dropdown-item transition-all duration-[0.3s] ease-in-out font-Poppins py-[7px] px-[5px] bg-[#fff] relative capitalize text-[13px] text-[#777] hover:text-[#64b496] whitespace-nowrap tracking-[0.03rem] block w-full"
                                                v-text="thirdLevelMenu.name"
                                            >
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </template>
                    </div>
                </div>
            </li>
        </ul>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',
            inject: ['store'],

            computed: {
                isLoading() {
                    return this.store.loading;
                },
                categories() {
                    return this.store.categories || [];
                },
                menus() {
                    return this.categories.map(menu => {
                        if (!menu?.children?.length) {
                            return menu;
                        }
                        return {
                            ...menu,
                            'submenus': menu.children.reduce((acc, item) => {
                                if (!item?.children?.length) {
                                    return { ...acc, 'flat': [...acc.flat, item] };
                                }
                                return { ...acc, 'nested': [...acc.nested, item] };
                            }, { flat: [], nested: [] })
                        };
                    });
                }

            },
            methods: {
                isMegaMenu(menu) {
                    return menu.submenus?.flat?.length > 0 && menu.submenus?.nested?.length > 0;
                }
            },

            mounted() {
                this.store.getCategories("{{ route('shop.api.categories.tree') }}")
            },
        });
    </script>
@endPushOnce
