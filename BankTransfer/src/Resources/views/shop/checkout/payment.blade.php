
<v-bank-details v-if="canPlaceOrder && cart?.payment_method == 'bank_transfer'"></v-bank-details>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bank-details-template">
        <x-licious::accordion  class="!border-b-0">
            <!-- Accordion Blade Component Header -->
            <x-slot:header class="!py-4 !px-0">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium max-sm:text-xl">
                        Bank Details
                    </h2>
                </div>
            </x-slot:header>

            <!-- Accordion Blade Component Content -->
            <x-slot:content class="!p-0 mt-8">
                <div class="flex flex-wrap gap-8">
                    <div>@{{ info.value.account }}</div>
                    <div>@{{ info.value.note }}</div>
                </div>
            </x-slot:content>
        </x-licious::accordion>
    </script>

    <script type="module">
        app.component('v-bank-details', {
            template: '#v-bank-details-template',
            data() {
                return {
                    info: @json(\Webkul\Payment\Payment::getAdditionalDetails('bank_transfer')),
                };
            },
        });
    </script>
@endPushOnce
