<v-mobile-menu></v-mobile-menu>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-menu-template">
        <ul v-if="menus.length" class="cr-mobile-menu-list">
            <template v-for="(menu) in menus">
                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.menu.before') !!}

                <li class="dropdown drop-list relative leading-[28px]">
                    <span class="flex justify-between items-center">

                        <a
                            :href="menu.url"
                            class="dropdown-list py-[12px] grow capitalize text-[15px] font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                            v-text="menu.name"
                        >
                        </a>
                        <span v-if="menu.children.length"
                            class="text-2xl items-center cursor-pointer bg-transparent"
                            :class="{
                                'ri-arrow-drop-up-line': activeMenu == menu.key,
                                'ri-arrow-drop-right-line': activeMenu != menu.key
                            }"
                            @click="toggle(menu.key)"></span>
                    </span>

                    <template v-if="activeMenu == menu.key">
                        <ul class="sub-menu w-full mb-[0] p-[0] min-w-auto opacity-[1]">
                            <li v-for="secondLevelMenu in menu.children">
                                <div class="flex justify-between items-center ltr:ml-3 rtl:mr-3 border border-b border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                    <a
                                        :href="secondLevelMenu.url"
                                        class="transition-all duration-[0.3s] ease-in-out pl-[20px] opacity-[0.8] text-[14px] py-[12px] block capitalize font-medium text-[#444] border-b-[1px] border-solid border-[#e9e9e9]"
                                        v-text="secondLevelMenu.name"
                                    >
                                    </a>

                                    <span
                                        v-if="secondLevelMenu.children.length"
                                        class="text-2xl cursor-pointer"
                                        :class="{
                                            'ri-arrow-drop-up-line': activeSubMenu == secondLevelMenu.key,
                                            'ri-arrow-drop-right-line': activeSubMenu != secondLevelMenu.key
                                        }"
                                        @click="toggleSecond(secondLevelMenu.key)"
                                    >
                                    </span>
                                </div>

                                <div v-if="activeSubMenu == secondLevelMenu.key">
                                    <ul>
                                        <li v-for="thirdLevelMenu in secondLevelMenu.children">
                                            <div class="flex justify-between items-center ltr:ml-3 rtl:mr-3 border border-b border-l-0 border-r-0 border-t-0 border-[#f3f3f5]">
                                                <a
                                                    :href="thirdLevelMenu.url"
                                                    class="flex items-center justify-between mt-5 ltr:ml-3 rtl:mr-3 pb-5"
                                                    v-text="thirdLevelMenu.name"
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
        app.component('v-mobile-menu', {
            template: '#v-mobile-menu-template',

            data() {
                return  {
                    activeMenu: null,
                    activeSubMenu : null,
                    menuData: @json(Storage::json('menus.json')),
                }
            },

            computed: {
                menus() {
                    return this.menuData.menus?.map((menu) => ({
                        ...menu,
                        isOpen: menu.key === this.activeMenu?.key ? ! menu.isOpen : false,
                    }));
                },
            },


            methods: {
                toggle(menu) {
                    this.activeMenu = this.activeMenu == menu ? null : menu;
                },
                toggleSecond(subMenu) {
                    this.activeSubMenu = this.activeSubMenu  == subMenu ? null : subMenu;
                },
            },
        });
    </script>
@endPushOnce
