<v-filter-checkbox {{ $attributes }}></v-filter-checkbox>

@pushOnce('scripts')
<!-- Filters Vue template -->
<script type="text/x-template" id="v-filter-checkbox-template">
    <!-- Checkbox Filter Options -->
    <div class="cr-checkbox pt-[28px] max-[991px]:pt-[30px]">
        <div
            :key="option.id"
            v-for="option in options"
            class="checkbox-group flex items-center relative mb-[15px]">
            <div class="items-center flex gap-x-4 ltr:pl-2 rtl:pr-2 rounded hover:bg-gray-100 select-none">
                <input
                    type="checkbox"
                    :id="'option_' + option.id"
                    class="h-[initial] w-[initial] m-[0] p-[0] hidden cursor-pointer"
                    :value="option.id"
                    @change="$emit('change', option)" />

                <label
                    class="relative font-Poppins text-[14px] text-[#7a7a7a] cursor-pointer capitalize inline-block"
                    role="checkbox"
                    aria-checked="false"
                    :aria-label="option.name"
                    :aria-labelledby="'label_option_' + option.id"
                    tabindex="0"
                    :for="'option_' + option.id"
                    v-text="option.name">
                </label>

                <span
                    class="font-Poppins text-[12px] text-[#7a7a7a] absolute right-[0]"
                    v-text="option.name">
                </span>
            </div>
        </div>
    </div>
</script>

<script type='module'>
    app.component('v-filter-checkbox', {
        template: '#v-filter-checkbox-template',
        props: ['options'],
    })
</script>
@endPushOnce
