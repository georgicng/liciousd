<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $title ?? '' }}
    </x-slot>

    <!-- Page Content -->
    <div class="container px-[60px] max-lg:px-8 max-sm:px-4">
        <x-licious::layouts.account.breadcrumb />

        <div class="flex gap-10 items-start mt-8 max-lg:gap-5 max-md:grid">
            <x-licious::layouts.account.navigation />

            <div class="flex-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-licious::layouts>
