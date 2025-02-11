<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $category->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app('Webkul\Product\Helpers\SEO')->getCategoryJsonLd($category) !!}
        </script>
    @endif
@endPush

<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.categories.view.banner_path.before') !!}

    <!-- Hero Image -->
    @if ($category->banner_path)
        <div class="container mt-8 px-[60px] max-lg:px-8 max-sm:px-4">
            <div>
                <img
                    class="rounded-xl"
                    src="{{ $category->banner_url }}"
                    alt="{{ $category->name }}"
                    width="1320"
                    height="300"
                >
            </div>
        </div>
    @endif

    {!! view_render_event('bagisto.shop.categories.view.banner_path.after') !!}

    <section class="section-shop py-[100px] max-[1199px]:py-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">

            {!! view_render_event('bagisto.shop.categories.view.description.before') !!}

            @if (in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
                <div class="flex flex-wrap hidden">
                    <div class="w-full px-[12px]">
                        <div class="mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                            <div class="cr-banner mb-[15px] text-center">
                                <h2 class="font-Manrope text-[32px] font-bold leading-[1.2] text-[#2b2b2d] max-[1199px]:text-[28px] max-[991px]:text-[25px] max-[767px]:text-[22px]">Categories</h2>
                            </div>
                            <div class="cr-banner-sub-title w-full">
                                @if ($category->description)
                                    {!! $category->description !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endif

            {!! view_render_event('bagisto.shop.categories.view.description.after') !!}

            @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
                <!-- Category Vue Component -->
                <x-licious::categories :category="$category" />
            @endif
        </div>
    </section>

</x-licious::layouts>
