@props([
    'name'     => 'rating',
    'value'    => 0,
    'disabled' => true,
])

<v-star-rating
    {{ $attributes }}
    name="{{ $name }}"
    value="{{ $value }}"
    disabled="{{ $disabled }}"
>
    {{ $slot }}
</v-star-rating>

@pushOnce("scripts")
    <script
        type="text/x-template"
        id="v-star-rating-template"
    >
        <div class="cr-star mr-[10px]">
            <i
                class="text-[#f5885f] text-[24px] cursor-pointer"
                role="presentation"
                v-for="rating in availableRatings"
                v-if="! disabled"
                :class="[appliedRatings >= rating ? 'ri-star-fill' : 'ri-star-line']"
                @click="change(rating)"
            >
            </i>

            <i
                class="text-[#f5885f] text-[16px]"
                role="presentation"
                v-for="rating in availableRatings"
                :class="[appliedRatings >= rating ? 'ri-star-fill' : 'ri-star-line']"
                v-else
            >
            </i>

            <v-field
                type="hidden"
                :name="name"
                v-model="appliedRatings"
            ></v-field>
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component("v-star-rating", {
            template: "#v-star-rating-template",

            props: [
                "name",
                "value",
                "disabled",
            ],

            data() {
                return {
                    availableRatings: [1, 2, 3, 4, 5],

                    appliedRatings: this.value,
                };
            },

            methods: {
                change(rating) {
                    this.appliedRatings = rating;

                    this.$emit('change', this.appliedRatings);
                },
            },
        });
    </script>
@endPushOnce
