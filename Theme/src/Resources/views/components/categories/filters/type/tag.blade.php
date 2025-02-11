<v-filter-tag {{ $attributes }} />

@pushOnce('scripts')
<!-- Filters Vue template -->
<script type="text/x-template" id="v-filter-tag-template">
    <div class="cr-shop-tags-inner pt-[25px]">
        <ul
            :key="option.id"
            v-for="option in options"
            class="cr-tags m-[-5px] p-[0] flex flex-wrap">
            <li>
                <a href="javascript:void(0)"
                    class="transition-all duration-[0.3s] ease-in-out h-[32px] px-[15px] m-[5px] rounded-[5px] font-Poppins text-[14px] bg-[#fff] text-[#7a7a7a] border-[1px] border-solid border-[#e9e9e9] leading-[30px] inline-block capitalizec hover:text-[#fff] hover:bg-[#64b496] hover:border-[#64b496]"
                    role="button"
                    aria-checked="false"
                    :aria-label="option.name"
                    :aria-labelledby="'label_option_' + option.id"
                    tabindex="0"
                    v-text="option.name">
                </a>
            </li>
        </ul>
    </div>
</script>

<script type='module'>
    app.component('v-filter-tag', {
        template: '#v-filter-tag-template',
        props: ['options'],
    })
</script>
@endPushOnce
