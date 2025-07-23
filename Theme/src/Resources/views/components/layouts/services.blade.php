{!! view_render_event('bagisto.shop.layout.features.before') !!}

<!--
    The ThemeCustomizationRepository repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type'       => 'services_content',
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
@endphp

<!-- Features -->
@if ($customization)
    <section class="section-services pt-[100px] max-[1199px]:pt-[70px] pb-[100px] max-[1199px]:pb-[70px] relative">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
            <div class="flex flex-wrap w-full">
                <div class="w-full px-[12px]">
                    <div class="cr-services-border" data-aos="fade-up" data-aos-duration="2000">
                        <div class="cr-service-slider swiper-container">
                            <swiper
                                :slides-per-view="4"
                                :pagination-clickable="true"
                                :space-between="24"
                                :breakpoints="{
                                    1399: {
                                        slidesPerView: 4,
                                        spaceBetween: 24
                                    },
                                    1028: {
                                        slidesPerView: 3,
                                        spaceBetween: 24
                                    },
                                    480: {
                                        slidesPerView: 2,
                                        spaceBetween: 24
                                    },
                                    0: {
                                        slidesPerView: 1,
                                        spaceBetween: 10
                                    }
                                }">
                                @foreach ($customization->options['services'] as $service)
                                    <swiper-slide>
                                        <div class="cr-services p-[24px] bg-[#f7f7f8] rounded-[5px] border-[1px] border-solid border-[#e9e9e9] flex flex-col max-[767px]:justify-center">
                                            <div class="cr-services-image mt-auto mr-auto mb-[12px] ml-auto block">
                                                <i class="{{$service['service_icon']}} text-[43px] leading-[40px] text-[#64b496]"></i>
                                            </div>
                                            <div class="cr-services-contain max-[767px]:text-center">
                                                <h4 class="mb-[5px] text-[18px] font-Poppins text-[#2b2b2d] leading-[1.667] font-semibold text-center max-[1399px]:text-[17px] max-[767px]:text-[15px]">{{$service['title']}}</h4>
                                                <p class="font-Poppins text-[14px] leading-[22px] font-light text-center text-[#7a7a7a]">{{$service['description']}}</p>
                                            </div>
                                        </div>
                                    </swiper-slide>
                                @endforeach
                            </swiper>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}
