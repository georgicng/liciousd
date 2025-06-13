<v-currency-switcher {{ $attributes }}></v-currency-switcher>

@pushOnce('scripts')
    <script type="text/x-template" id="v-currency-switcher-template">
    <div class="gap-1 mt-2.5 pb-2.5" :class="{ 'flex flex-wrap': position == 'horizontal', 'grid': position == 'vertical' }">
            <span
                class="px-5 py-2 text-base cursor-pointer hover:bg-gray-100"
                v-for="currency in currencies"
                :class="{'bg-gray-100': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                @click="change(currency)"
            >
                @{{ currency.symbol + ' ' + currency.code }}
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',

            props: {
                position: {
                    type: String,
                    default: 'horizontal',
                }
            },

            data() {
                return {
                    currencies: @json(core()->getCurrentChannel()->currencies),
                };
            },

            methods: {
                change(currency) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('currency', currency.code);

                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
