@props([
    'isActive' => false,
])

<v-drawer
    :is-active="{{ $isActive }}"
>
    @isset($toggle)
        <template #toggle="{ open, toggle }">
            {{ $toggle }}
        </template>
    @endisset

    @isset($overlay)
        <template #overlay="{ isOpen }">
            {{ $overlay }}
        </template>
    @endisset

    <template #default="{ close }">
        {{ $slot }}
    </template>

</v-drawer>

@pushOnce('scripts')
    <script type="text/x-template" id="v-drawer-template">

        <!-- Toggler -->
        <slot name="toggle" :open="open" :toggle="toggle"></slot>

        <Teleport to="#app">
            <!-- Overlay -->
            <slot name="overlay" :isOpen="isOpen"></slot>

            <!-- Content -->
            <div
                {{ $attributes->merge(['class' => 'fixed h-screen z-[25] top-[0] bg-[#fff] p-[0] m-[0] w-[350px] max-[575px]:w-[300px] max-[420px]:w-[250px] transition-all duration-[0.4s] ease overflow-x-auto']) }}
                v-if="isOpen"
            >
                <!-- Content Slot -->
                <slot :close="close"></slot>
            </div>
        </Teleport>
    </script>

    <script type="module">
        app.component('v-drawer', {
            template: '#v-drawer-template',

            props: {
                isActive: {
                    type: Boolean,
                    default: false
                },
            },

            data() {
                return {
                    isOpen: this.isActive,
                };
            },

            watch: {
                isActive: function(newVal, oldVal) {
                    this.isOpen = newVal;
                }
            },

            methods: {
                toggle() {
                    this.isOpen = ! this.isOpen;

                    if (this.isOpen) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow ='scroll';
                    }

                    this.$emit('toggle', { isActive: this.isOpen });
                },

                open() {
                    this.isOpen = true;

                    document.body.style.overflow = 'hidden';

                    this.$emit('open', { isActive: this.isOpen });
                },

                close() {
                    this.isOpen = false;

                    document.body.style.overflow = 'auto';

                    this.$emit('close', { isActive: this.isOpen });
                }
            },
        });
    </script>
@endPushOnce
