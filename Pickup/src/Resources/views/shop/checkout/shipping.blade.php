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
            <x-slot:content class="!p-0 mt-2">
                <!-- Pickup Point Info -->
                <div>
                    <template v-if="station.location">
                        <div class="w-full h-[260px]" v-html="station.location"></div>
                    </template>
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-medium text-[#1c1c1c]">@{{ station.name }}</p>
                            <p class="text-[#6e6e73]">@{{ station.address }}</p>
                            <p class="text-[#6e6e73]">off @{{ station.landmark }}</p>
                            <p class="text-[#6e6e73]">@{{ station.city }}</p>
                            <div class="flex items-center gap-1 mt-1 text-[#1c1c1c]">
                                <i class="ri-phone-line text-[#f59e0b] mr-1"></i>
                                <span class="text-[#1c1c1c]">@{{ station.phone }}</span>
                            </div>
                            <div v-if="station.whatsapp" class="flex items-center gap-1 mt-1 text-[#1c1c1c]">
                                <i class="ri-whatsapp-line text-[#f59e0b] mr-1"></i>
                                <span class="text-[#1c1c1c]">@{{ station.whatsapp  }}</span>
                            </div>
                        </div>
                        <div class="text-[#1c1c1c] font-medium">@{{ station.rate }}</div>
                    </div>

                    <!-- Opening Hours -->
                    <div v-if="station.additional" class="gap-y-2.5 relative text-sm">
                        <button
                            class="font-medium text-sm items-center inline-flex"
                            type="button"
                            @click="toggle = !toggle">
                            <span class="font-medium text-[#1c1c1c] mb-1">Opening hours</span>
                            <span
                                class="text-2xl"
                                :class="{'ri-arrow-up-s-line': toggle, 'ri-arrow-down-s-line': !toggle}"
                            ></span>
                        </button>
                        <div
                            class="bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-60 dark:bg-gray-700 dark:divide-gray-600 space-y-1 text-[#1c1c1c]"
                            :class="{ 'hidden': !toggle, 'absolute z-1000': toggle }">
                            <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200">
                                <li v-for="day in Object.keys(days)" class="flex justify-between gap-2 items-center">
                                    <span class="font-medium" v-text="`${days[day]}:`"></span>
                                    <span> @{{ station.additional[day]?.status === 'on'? `${station.additional[day]?.opens}:00 - ${station.additional[day]?.closes}:00` : 'Closed' }} </span>
                                </li>
                            </ul>
                        </div>
                    </div>
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
                    days: {
                        monday: 'Monday',
                        tuesday: 'Tuesday',
                        wednesday: 'Wednesday',
                        thursday: 'Thursday',
                        friday: 'Friday',
                        saturday: 'Saturday',
                        sunday: 'Sunday'
                    },
                    toggle: false
                };
            },
            computed: {
                station() {
                    return this.locations[this.method];
                }
            }
        });
    </script>
@endPushOnce
