<x-licious::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('licious::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-licious::breadcrumbs name="orders" />
    @endSection

    <div class="flex justify-between items-center">
        <div class="">
            <h2 class="text-2xl font-medium">
                @lang('licious::app.customers.account.orders.title')
            </h2>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

    <x-licious::datagrid :src="route('shop.customers.account.orders.index')" />

    {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}

</x-licious::layouts.account>
