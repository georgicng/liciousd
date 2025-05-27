
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
            <x-slot:content class="!p-0 mt-2">
                <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="account in getTokens(info.value.account)" class="py-3 sm:py-4">
                        <div class="flex items-center gap-2 space-x-4 rtl:space-x-reverse">
                            <div class="shrink-0">
                                <i class="ri-bank-line w-8 h-8 rounded-full"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                   @{{ account.bank }}
                                </p>
                                <p class="font-semibold text-gray-500 truncate dark:text-gray-400">
                                    @{{ account.number }}
                                </p>
                            </div>
                            <div class="inline-flex items-center text-sm text-gray-900 dark:text-white">
                                @{{ account.name }}
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="block">
                    <blockquote class="p-4 my-4 border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">@{{ info.value.note }}</blockquote>
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
            methods: {
                getTokens(value) {
                    const lines = value.split('\n');
                    return lines.map(line => {
                        const [bank, accountName, accountNumber] = line.split('|');
                        return {
                            bank:  bank?.trim(),
                            name:  accountName?.trim(),
                            number:  accountNumber?.trim(),
                        }
                    });
                }
            }
        });
    </script>
@endPushOnce
