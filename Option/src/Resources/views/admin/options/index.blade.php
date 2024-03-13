<x-admin::layouts>
    <x-slot:title>
        @lang('option::app.admin.catalog.options.index.title')
    </x-slot:title>

    <div class="flex items-center justify-between gap-[16px] max-sm:flex-wrap">
        {{-- Title --}}
        <p class="text-[20px] font-bold text-gray-800 dark:text-white">
            @lang('option::app.admin.catalog.options.index.title')
        </p>

        <div class="flex items-center gap-x-[10px]">
            @if (bouncer()->hasPermission('admin.options.create'))
                <a href="{{ route('admin.options.create') }}">
                    <div class="cursor-pointer rounded-[6px] border border-blue-700 bg-blue-600 px-[12px] py-[6px] font-semibold text-gray-50">
                        @lang('option::app.admin.catalog.options.index.create-btn')
                    </div>
                </a>
            @endif
        </div>
    </div>

    {!! view_render_event('bagisto.admin.options.list.before') !!}

    <x-admin::datagrid :src="route('admin.options.index')"></x-admin::datagrid>

    {!! view_render_event('bagisto.admin.options.list.after') !!}

</x-admin::layouts>
