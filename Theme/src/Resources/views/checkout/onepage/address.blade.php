{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<!-- Accordian Blade Component -->
<x-licious::accordion class="mt-8 mb-7 !border-b-0">
    <!-- Accordian Header Component Slot -->
    <x-slot:header class="!p-0">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-medium max-sm:text-xl">
                @lang('licious::app.checkout.onepage.address.title')
            </h2>
        </div>
    </x-slot>

    <!-- Accordian Content Component Slot -->
    <x-slot:content class="!p-0 mt-8">
        <!-- If the customer is guest -->
        <template v-if="cart.is_guest">
            @include('shop::checkout.onepage.address.guest')
        </template>

        <!-- If the customer is logged in -->
        <template v-else>
            @include('shop::checkout.onepage.address.customer')
        </template>
    </x-slot:content>
</x-licious::accordion>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}
