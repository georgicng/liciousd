<v-pickup-center v-if="cart?.selected_shipping_rate_method?.includes('Pickup Centre')" :method="cart.selected_shipping_rate_method">
</v-pickup-center>

@pushOnce('scripts')
    <script type="text/x-template" id="v-pickup-center-template">
        <x-licious::accordion  class="!border-b-0">
            <!-- Accordion Blade Component Header -->
            <x-slot:header class="!py-4 !px-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        Pickup Details
                    </h2>
                </div>
            </x-slot:header>

            <!-- Accordion Blade Component Content -->
            <x-slot:content class="!p-0 mt-8">
                <div class="flex flex-wrap gap-8">
                    <dl>
                        <dt>Center</dt><dd>@{{ location.name }}</dd>
                        <dt>Address</dt><dd>@{{ location.address }}</dd>
                        <dt>Phone</dt><dd>@{{ location.phone }}</dd>
                    </dl>
                </div>
            </x-slot:content>
        </x-licious::accordion>
    </script>

    <script type="module">
        app.component('v-pickup-center', {
            template: '#v-pickup-center-template',
            props: {
                method: {
                    type: String,
                    required: true
                }
            },

            data() {
                return {
                    locations: @json(getPickupLocations()),
                };
            },
            computed: {
                location() {
                    return this.locations[this.method];
                }
            }
        });
    </script>
@endPushOnce
