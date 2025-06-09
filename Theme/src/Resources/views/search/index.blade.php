@inject('categoryRepository','Webkul\Category\Repositories\CategoryRepository')
@php
    if (request()->has('query')) {
        $title = trans('shop::app.search.title', ['query' => request()->query('query')]);
    } else {
        $title = trans('shop::app.search.results');
    }

    $category = null;

     if (request()->has('category')) {
        $category = $categoryRepository->find(request()->query('category'));
    }
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ $title }}"/>

    <meta name="keywords" content="{{ $title }}"/>
@endPush

<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $title }}
    </x-slot>


    <section class="section-shop py-[100px] max-[1199px]:py-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">

            {!! view_render_event('bagisto.shop.search.view.description.before') !!}
                <div class="flex flex-wrap hidden">
                    <div class="w-full px-[12px]">
                        <div class="mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                            <div class="cr-banner mb-[15px] text-center">
                                <h2 class="font-Manrope text-[32px] font-bold leading-[1.2] text-[#2b2b2d] max-[1199px]:text-[28px] max-[991px]:text-[25px] max-[767px]:text-[22px]">{{ $title }}</h2>
                            </div>
                            <div class="cr-banner-sub-title w-full">
                                @if ($category && $category->description)
                                    {!! $category->description !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            {!! view_render_event('bagisto.shop.search.view.description.after') !!}
                <!-- Category Vue Component -->
            <x-licious::categories :category="$category" />
        </div>
    </section>

</x-licious::layouts>

