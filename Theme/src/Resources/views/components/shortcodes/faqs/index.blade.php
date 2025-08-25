<div class="cr-faq">
    <div class="cr-accordion style-1 mb-[-10px]">
        {!! $slot !!}
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-accordion-template">
        <div class="cr-accordion-item overflow-hidden mb-[10px] border-[1px] border-solid border-[#eee] rounded-[5px]">
            <h4
                class="accordion-head active-arrow m-[0] p-[14px] text-[#4b5966] text-[16px] leading-[20px] font-medium relative border-b-[1px] border-solid border-[#eee] font-Poppins cursor-pointer tracking-[0] max-[767px]:text-[15px]"
                :class="isOpen ? 'active-arrow' : ''"
                @click="toggle">
                @{{ question }}
            </h4>
            <div v-show="isOpen" class="accordion-body py-[15px] p-[15px] text-[14px] font-Poppins text-[#7a7a7a] leading-[1.75]">
                <div v-html="answer"></div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-accordion', {
            template: '#v-accordion-template',

            props: [
                'isActive',
                'question',
                'answer',
            ],

            data() {
                return {
                    isOpen: this.isActive,
                };
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    this.$emit('toggle', { isActive: this.isOpen });
                },
            },
        });
    </script>
@endPushOnce
