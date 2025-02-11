@props(['position' => 'left', 'name' => 'my'])

<v-tabs {{ $attributes }}>
    <x-licious::shimmer.tabs />
</v-tabs>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tabs-template"
    >
        <div class="cr-paking-delivery mt-[40px] p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]">
            <ul
                class="nav nav-tabs border-b-[1px] border-solid border-[#dee2e6] flex flex-wrap justify-left"
                id="{{$name}}Tab">
                <li
                    tabindex="0"
                    v-for="tab in tabs"
                    class="nav-item transition-all duration-[0.3s] ease-in-out mr-[30px] relative"
                    :class="{'active': tab.isActive }">
                    <a
                        :href="`#${tab.title}`"
                        role="button"
                        class="mb-[25px] flex font-Poppins text-[17px] font-semibold leading-[1.5] tracking-[0] text-[#2b2b2d] text-left max-[1399px]:text-[18px] max-[767px]:text-[16px] max-[575px]:mb-[15px]"
                        v-text="tab.title"
                        @click="change(tab)"></a>
                </li>
            </ul>

            <div class="tab-content" id="{{$name}}TabContent">
                {{ $slot }}
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tabs', {
            template: '#v-tabs-template',

            props: ['position'],

            data() {
                return {
                    tabs: []
                }
            },

            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.title == selectedTab.title);
                    });
                },
            },
        });
    </script>
@endPushOnce
