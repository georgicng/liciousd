<v-pickup-center v-if="['payment', 'review'].includes(currentStep) && cart?.selected_shipping_rate_method?.includes('Pickup Centre')" :method="cart.selected_shipping_rate_method" :currentStep="currentStep">
    <v-tabs position="left" name="pickup-center">
        <v-tab-item title="Pickup Center" is-selected="true">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-medium max-sm:text-xl">Pickup Center</h2>
            </div>
            <p class="text-[#6e6e73]">Select a pickup center to collect your order.</p>
        </v-tab-item>
    </v-tabs>
</v-pickup-center>

@pushOnce('scripts')
    <script type="text/x-template" id="v-pickup-center-template">
        <x-shop::accordion  class="!border-b-0">
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
                <v-stepper>
                    <v-item title="Address" :isSelected="true">
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
                    </v-item>
                    <v-item v-if="station.additional" title="Opening Hours">
                        <!-- Opening Hours -->
                        <div class="bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-60 dark:bg-gray-700 dark:divide-gray-600 space-y-1 text-[#1c1c1c]">
                            <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200">
                                <li v-for="day in Object.keys(days)" class="flex justify-between gap-2 items-center">
                                    <span class="font-medium" v-text="`${days[day]}:`"></span>
                                    <span> @{{ station.additional[day]?.status === 'on'? `${station.additional[day]?.opens}:00 - ${station.additional[day]?.closes}:00` : 'Closed' }} </span>
                                </li>
                            </ul>
                        </div>
                    </v-item>
                    <v-item v-if="station.location" title="Directions">
                        <div class="w-full h-[260px]" v-html="station.location"></div>
                    </v-item>
                </v-stepper>
            </x-slot:content>
        </x-shop::accordion>
    </script>

    <script type="text/x-template" id="v-stepper-template">
        <div class='cr-paking-delivery mt-[40px] p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]'>
            <ul class='nav nav-tabs border-b-[1px] border-solid border-[#dee2e6] flex flex-wrap justify-left'>
                <li v-for='(tab, index) in tabs'
                    :key='tab.title'
                    @click='change(tab)'
                    class="nav-item transition-all duration-[0.3s] ease-in-out mr-[30px] relative"
                    :class='{"active": tab.isActive}'>
                    @{{ tab.title }}
                </li>
            </ul>
            <slot></slot>
        </div>
    </script>

    <script type="text/x-template" id="v-item-template">
        <div class="tab-delivery-pane" v-show='isActive'>
            <div class="cr-tab-content">
                <slot></slot>
            </div>
        </div>
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

        app.component('v-stepper', {
            template: '#v-stepper-template',

            props: {
                mode: {
                    type: String,
                    default: 'light'
                }
            },
            data () {
                return {
                    tabs: []         // all of the tabs
                }
            },
            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.title == selectedTab.title);
                    });
                },
            }
        });

        app.component('v-item', {
            template: '#v-item-template',

            props: ['title', 'isSelected'],
            data () {
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
