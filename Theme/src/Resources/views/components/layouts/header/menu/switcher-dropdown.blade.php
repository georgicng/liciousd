<div class="cr-category-icon-block py-[10px] max-[991px]:hidden">
    <div class="cr-category-menu relative">
        <div class="cr-category-toggle w-[35px] h-[35px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] cursor-pointer flex items-center justify-center">
            <i class="ri-menu-2-line text-[22px] text-[#2b2b2d] leading-[14px] block"></i>
        </div>
    </div>
    <div class="cr-cat-dropdown transition-all duration-[0.3s] ease-in-out w-[600px] mt-[15px] p-[15px] absolute bg-[#fff] opacity-0 invisible left-[12px] z-[10] rounded-[5px] border-[1px] border-solid border-[#e9e9e9]">
        <div class="cr-cat-block">
            <v-tab-pills />
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tab-pills-template"
    >
        <div class="cr-cat-tab flex">
            <ul class="cr-tab-list nav flex-column nav-pills min-w-[180px] mr-[12px] rounded-[5px] flex flex-wrap flex-col justify-center" id="myTab">
                <li role="button" class="transition-all duration-[0.3s] ease-in-out py-[10px] px-[15px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] flex items-center cursor-pointer mb-[5px]" :class="{'active': active == 0}" @click="change(0)">
                    <span class="text-[13px] text-[#4b5966] tracking-[0] font-medium text-left capitalize">Currency</span>
                </li>
                <li role="button" class="transition-all duration-[0.3s] ease-in-out py-[10px] px-[15px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] flex items-center cursor-pointer mb-[5px]" :class="{'active': active == 1}" @click="change(1)">
                    <span class="text-[13px] text-[#4b5966] tracking-[0] font-medium text-left capitalize">Locale</span>
                </li>
            </ul>
            <div class="tab-content transition-all duration-[0.3s] ease-in-out w-full">
                <div v-if="active == 0" class="tab-pane">
                    <div class="tab-list flex flex-wrap w-full">
                        <x-licious::switcher.currency />
                    </div>
                </div>
                <div v-if="active == 1" class="tab-pane">
                    <div class="tab-list flex flex-wrap w-full">
                        <x-licious::switcher.locale />
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tab-pills', {
            template: '#v-tab-pills-template',

            data() {
                return {
                    active: 0
                }
            },

            methods: {
                change(index) {
                    this.active = index;
                },
            },
        });
    </script>
@endPushOnce
