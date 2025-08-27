<!-- SEO Meta Content -->
@push('meta')
    <meta name="title" content="{{ $page->meta_title }}" />

    <meta name="description" content="{{ $page->meta_description }}" />

    <meta name="keywords" content="{{ $page->meta_keywords }}" />
@endPush

<!-- Page Layout -->
<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $page->meta_title }}
    </x-slot>

    <x-licious::breadcrumbs name="page" :entity="$page" :title="$page->page_title" />

    {!! app('shortcode')->render($page->html_content)  !!}

</x-licious::layouts>
