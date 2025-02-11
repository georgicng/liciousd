<v-search>
</v-search>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-search-template"
    >
        <form class="cr-search relative max-[575px]:max-w-[350px] max-[575px]:m-auto">
            <input
                class="search-input w-[600px] h-[45px] pl-[15px] pr-[175px] border-[1px] border-solid border-[#64b496] rounded-[5px] outline-[0] max-[1399px]:w-[400px] max-[991px]:max-w-[350px] max-[575px]:w-full max-[420px]:pr-[45px]"
                type="text"
                placeholder="Search For items...">
            <select class="form-select mr-[10px] w-[120px] h-[calc(100%-2px)] border-[0] tracking-[0] absolute top-[1px] pt-[0.375rem] pb-[0.375rem] pl-[0.5rem] outline-[0] right-[45px] text-[13px] border-l-[1px] border-solid border-[#64b496] rounded-[0] max-[420px]:hidden" aria-label="Default select example">
                <option value="" selected>All Categories</option>
                <option v-for="(category) in categories" :key="category.id"  value="category.id">@{{ category.name }}</option>
            </select>
            @if (core()->getConfigData('general.content.shop.image_search'))
                @include('shop::search.images.index')
            @endif
            <a href="javascript:void(0)" class="search-btn w-[45px] bg-[#64b496] absolute right-[0] top-[0] bottom-[0] rounded-r-[5px] flex items-center justify-center">
                <i class="ri-search-line text-[14px] text-[#fff]"></i>
            </a>
        </form>
    </script>

    <script type="module">
        app.component('v-search', {
            template: '#v-search-template',

            inject: ['store'],

            computed: {
                categories() {
                    return this.store.categories;;
                },
            },

            async mounted() {
                await this.store.getCategories("{{ route('shop.api.categories.tree') }}");
            },
        });
    </script>
@endPushOnce
