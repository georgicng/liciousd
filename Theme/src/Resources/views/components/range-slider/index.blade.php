<v-range-slider {{ $attributes }}></v-range-slider>

@pushOnce('scripts')
<script type="text/x-template" id="v-range-slider-template">
    <div class="price-range-slider w-full mt-[30px]">

        <div id="slider-range" class="range-bar h-[3px] w-full ml-[8px] border-[0] bg-[#e9e9e9]">
            <div class="relative w-full h-1 bg-gray-200 rounded-2xl">
                <div
                    ref="progress"
                    class="absolute left-1/4 right-0 h-full bg-navyBlue rounded-xl"
                >
                </div>

                <span>
                    <input
                        ref="minRange"
                        type="range"
                        :value="minRange"
                        class="absolute w-full h-1 appearance-none pointer-events-none bg-transparent outline-none cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                        :min="allowedMinRange"
                        :max="allowedMaxRange"
                        aria-label="@lang('licious::app.components.range-slider.min-range')"
                        @input="handle('min', $event.target.value)"
                    >
                </span>

                <span>
                    <input
                        ref="maxRange"
                        type="range"
                        :value="maxRange"
                        class="absolute w-full h-1 appearance-none pointer-events-none bg-transparent outline-none cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-[18px] [&::-webkit-slider-thumb]:w-[18px] [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:ring-navyBlue [&::-webkit-slider-thumb]:ring [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:h-[18px] [&::-moz-range-thumb]:w-[18px] [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:ring-navyBlue [&::-moz-range-thumb]:ring [&::-ms-thumb]:pointer-events-auto [&::-ms-thumb]:bg-white [&::-ms-thumb]:appearance-none [&::-ms-thumb]:h-[18px] [&::-ms-thumb]:w-[18px] [&::-ms-thumb]:rounded-full [&::-ms-thumb]:ring-navyBlue [&::-ms-thumb]:ring"
                        :min="allowedMinRange"
                        :max="allowedMaxRange"
                        aria-label="@lang('licious::app.components.range-slider.max-range')"
                        @input="handle('max', $event.target.value)"
                    >
                </span>
            </div>
        </div>
        <p class="range-value my-[20px] flex">
            <label class="font-Poppins text-[15px] font-bold leading-[1.2] text-[#000] max-[1399px]:text-[14px]">@lang('licious::app.components.range-slider.range')</label>
            <input type="text" id="amount" placeholder="'" :value="rangeText" class="w-[calc(100%-50px)] pl-[6px] bg-[#f7f7f8] font-Poppins text-[15px] font-bold leading-[1.2] tracking-[0] text-[#7a7a7a] border-[0] outline-[0]" readonly>
        </p>
    </div>
</script>

<script type='module'>
    app.component('v-range-slider', {
        template: '#v-range-slider-template',

        props: {
            'defaultType': { type: String, default: 'price' },
            'allowedMinRange': { type: Number, default: 0 },
            'allowedMaxRange': { type: Number, default: 100 },
            'minRange': { type: Number, default: 0 },
            'maxRange': { type: Number, default: 100 },
        },

        data() {
            return {
                gap: this.allowedMaxRange * 0.10,

                supportedTypes: ['integer', 'float', 'price'],
            };
        },

        computed: {
            rangeText() {
                let {
                    formattedMinRange,
                    formattedMaxRange
                } = this.getFormattedData();

                return `${formattedMinRange} - ${formattedMaxRange}`;
            },
        },

        watch: {
            minRange() {
                this.handleProgressBar();
            },
            maxRange() {
                this.handleProgressBar();
            }
        },

        methods: {

            getFormattedData() {
                /**
                 * If someone is passing invalid props, this case will check first if they are valid, then continue.
                 */
                if (this.isTypeSupported()) {
                    switch (this.defaultType) {
                        case 'price':
                            return {
                                formattedAllowedMinRange: this.$shop.formatPrice(this.allowedMinRange),
                                    formattedAllowedMaxRange: this.$shop.formatPrice(this.allowedMaxRange),
                                    formattedMinRange: this.$shop.formatPrice(this.minRange),
                                    formattedMaxRange: this.$shop.formatPrice(this.maxRange),
                            };

                        case 'float':
                            return {
                                formattedAllowedMinRange: parseFloat(this.allowedMinRange).toFixed(2),
                                    formattedAllowedMaxRange: parseFloat(this.allowedMaxRange).toFixed(2),
                                    formattedMinRange: parseFloat(this.minRange).toFixed(2),
                                    formattedMaxRange: parseFloat(this.maxRange).toFixed(2),
                            };

                        default:
                            return {
                                formattedAllowedMinRange: this.allowedMinRange,
                                    formattedAllowedMaxRange: this.allowedMaxRange,
                                    formattedMinRange: this.minRange,
                                    formattedMaxRange: this.maxRange,
                            };
                    }
                }

                /**
                 * Otherwise, we will load the default formatting.
                 */
                return {
                    formattedAllowedMinRange: this.allowedMinRange,
                    formattedAllowedMaxRange: this.allowedMaxRange,
                    formattedMinRange: this.minRange,
                    formattedMaxRange: this.maxRange,
                };
            },

            handle(rangeType, evt) {
                let minRange = parseInt(rangeType == 'min' ? evt : this.minRange);

                let maxRange = parseInt(rangeType == 'max' ? evt : this.maxRange);

                if (maxRange - minRange < this.gap) {
                    if (rangeType === 'min') {

                        minRange = maxRange - this.gap;
                    } else {
                        maxRange = this.minRange + this.gap;
                    }
                }

                this.$emit('change-range', { minRange, maxRange });
            },

            handleProgressBar() {
                const direction = document.dir == 'ltr' ? 'left' : 'right';

                this.$refs.progress.style[direction] = (this.minRange / this.allowedMaxRange) * 100 + '%';

                this.$refs.progress.style[direction == 'left' ? 'right' : 'left'] = 100 - (this.maxRange / this.allowedMaxRange) * 100 + '%';
            },

            isTypeSupported() {
                return this.supportedTypes.includes(this.defaultType);
            },
        },
        mounted() {
            this.handleProgressBar();
        }
    });
</script>
@endPushOnce
