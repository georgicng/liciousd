<v-desktop-menu>
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
</v-desktop-menu>

@pushOnce('scripts')
    <script type="text/x-template" id="v-desktop-menu-template">
        <ul
            class="navbar-nav flex min-[992px]:flex-row items-center m-auto relative z-[3] min-[992px]:flex-row max-[1199px]:mr-[-5px] max-[991px]:m-[0]"
        >
            <li
                class="nav-item dropdown relative mr-[25px] max-[1399px]:mr-[20px] max-[1199px]:mr-[30px]"
                v-for="menu in menus"
            >
                <a
                    :href="menu.url"
                    class="nav-link font-Poppins text-[14px] font-medium block text-[#000] z-[1] flex items-center relative py-[11px] px-[8px] max-[1199px]:py-[8px] max-[1199px]:px-[0]"
                    :class="{ 'dropdown-toggle': menu.children.length }"
                    v-text="menu.name"
                >
                </a>

                <ul
                    class="dropdown-menu transition-all duration-[0.3s] ease-in-out py-[8px] min-w-[160px] mt-[35px] absolute text-left opacity-0 invisible left-auto bg-[#fff] rounded-[5px] block z-[9] border-[1px] border-solid border-[#e9e9e9]"
                    v-if="menu.children.length"
                >
                    <li
                        class="w-full mr-[0]"
                        v-for="pairMenuChildren in pairMenuChildren(menu)"
                    >
                        <template v-for="secondLevelMenu in pairMenuChildren">
                            <a
                                :href="secondLevelMenu.url"
                                class="dropdown-item transition-all duration-[0.3s] ease-in-out font-Poppins py-[7px] px-[20px] bg-[#fff] relative capitalize text-[13px] text-[#777] hover:text-[#64b496] whitespace-nowrap tracking-[0.03rem] block w-full"
                                v-text="secondLevelMenu.name"
                            >
                            </a>

                            <ul
                                class="grid grid-cols-[1fr] gap-3"
                                v-if="secondLevelMenu.children.length"
                            >
                                <li
                                    class="text-sm font-medium text-[#6E6E6E]"
                                    v-for="thirdLevelMenu in secondLevelMenu.children"
                                >
                                    <a
                                        :href="thirdLevelMenu.url"
                                        v-text="thirdLevelMenu.name"
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
        app.component('v-desktop-menu', {
            template: '#v-desktop-menu-template',

            computed: {
                menus() {
                    return this.menuData.menus;
                }
            },

            data() {
                return  {
                    menuData: @json(Storage::json('menus.json')),
                }
            },

            methods: {
                pairMenuChildren(menu) {
                    return menu.children.reduce((result, value, index, array) => {
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
