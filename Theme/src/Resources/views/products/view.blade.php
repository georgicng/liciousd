@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);

    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<!-- Page Layout -->
<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <!-- Breadcrumbs -->
    <x-licious::breadcrumbs
        name="product"
        :entity="$product"
    />

    <section class="section-product pt-[100px] max-[1199px]:pt-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">

            <!-- Product Information Vue Component -->
            <x-licious::products.item ::product-id="{{ $product->id }}" :$product :$customAttributeValues>
                <x-licious::shimmer.products.view />
            </x-licious::products.item>

            <!-- Information Section -->
            <div class="flex flex-wrap w-full" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                <div class="w-full px-[12px]">
                    <x-licious::tabs position="center">
                        <!-- Description Tab -->
                        {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                        <x-licious::tabs.item
                            :title="trans('shop::app.products.view.description')"
                            :is-selected="true"
                        >
                            <div class="container mt-[60px] max-1180:px-5">
                                <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                                    {!! $product->description !!}
                                </p>
                            </div>
                        </x-licious::tabs.item>

                        {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                        <!-- Additional Information Tab -->
                        @if(count($attributeData))
                            <x-licious::tabs.item
                                :title="trans('shop::app.products.view.additional-information')"
                                :is-selected="false"
                            >
                                <div class="list">
                                    <ul class="mt-[15px] p-[0] mb-[1rem]">
                                        @foreach ($customAttributeValues as $customAttributeValue)
                                        <li class="py-[5px] text-[#777] flex">
                                            @if (! empty($customAttributeValue['value']))
                                                <label class="min-w-[100px] mr-[10px] text-[#2b2b2d] font-semibold flex justify-between">
                                                    {!! $customAttributeValue['label'] !!} <span>:</span>
                                                </label>

                                                @if ($customAttributeValue['type'] == 'file')
                                                    <a
                                                        href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                        download="{{ $customAttributeValue['label'] }}"
                                                    >
                                                        <span class="icon-download text-2xl"></span>
                                                    </a>
                                                @elseif ($customAttributeValue['type'] == 'image')
                                                    <a
                                                        href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                                        download="{{ $customAttributeValue['label'] }}"
                                                    >
                                                        <img
                                                            class="h-5 w-5 min-h-5 min-w-5"
                                                            src="{{ Storage::url($customAttributeValue['value']) }}"
                                                        />
                                                    </a>
                                                @else
                                                    {!! $customAttributeValue['value'] !!}
                                                @endif
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </x-licious::tabs.item>
                        @endif

                        <!-- Reviews Tab -->
                        <x-licious::tabs.item
                            :title="trans('shop::app.products.view.review')"
                            :is-selected="false"
                        >
                            <x-licious::products.reviews :product="$product" />
                        </x-licious::tabs.item>
                    </x-licious::tabs>
                </div>
            </div>

        </div>
    </section>


    <!-- Featured Products -->
    <x-licious::products.carousel
        :title="trans('shop::app.products.view.related-product-title')"
        :src="route('shop.api.products.related.index', ['id' => $product->id])"
    />

    <!-- Upsell Products -->
    <x-licious::products.carousel
        :title="trans('shop::app.products.view.up-sell-title')"
        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
    />

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
</x-licious::layouts>
