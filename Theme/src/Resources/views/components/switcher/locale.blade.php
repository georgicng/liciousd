<v-locale-switcher {{ $attributes }}></v-locale-switcher>

@pushOnce('scripts')
    <script type="text/x-template" id="v-locale-switcher-template">
        <div class="gap-1 mt-2.5 pb-2.5" :class="{ 'flex flex-wrap': position == 'horizontal', 'grid': position == 'vertical' }">
            <span
                class="flex items-center gap-2.5 px-5 py-2 text-base cursor-pointer hover:bg-gray-100"
                v-for="locale in locales"
                :class="{'bg-gray-100': locale.code == '{{ app()->getLocale() }}'}"
                @click="change(locale)"
            >
                <img
                    :src="locale.logo_url || '{{ bagisto_asset('images/default-language.svg') }}'"
                    width="24"
                    height="16"
                />

                @{{ locale.name }}
            </span>
        </div>
    </script>

    <script type="module">
        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',

            props: {
                position: {
                    type: String,
                    default: 'horizontal',
                }
            },

            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },

            methods: {
                change(locale) {
                    let url = new URL(window.location.href);

                    url.searchParams.set('locale', locale.code);

                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
