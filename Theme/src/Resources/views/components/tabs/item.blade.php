@props([
    'title'      => '',
    'isSelected' => false,
])

<v-tab-item
    title="{{ $title }}"
    is-selected="{{ $isSelected }}"
>
    <template v-slot>
        {{ $slot }}
    </template>
</v-tab-item>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-tab-item-template"
    >
        <div v-if="isActive" class="tab-delivery-pane" :id="title">
            <div class="cr-tab-content">
                <slot></slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tab-item', {
            template: '#v-tab-item-template',

            props: ['title', 'isSelected'],

            data() {
                return {
                    isActive: false
                }
            },

            mounted() {
                this.isActive = this.isSelected;

                /**
                 * On mounted, pushing element to its parents component.
                 */
                this.$parent.$data.tabs.push(this);
            }
        });
    </script>
@endPushOnce
