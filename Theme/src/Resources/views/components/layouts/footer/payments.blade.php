<div class="cr-payment">
    <div class="cr-insta-slider swiper-container">
        <swiper
            :slides-per-view="4"
            :space-between="12"
            :pagination-clickable="true"
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
            @foreach (core()->getConfigData('sales.payment_methods') as $key => $payment)
                @if($payment['active'] && core()->getConfigData("sales.payment_methods.{$key}.image"))
                    <swiper-slide>
                        <div class="swiper-slide">
                            <a href="#" class="cr-payment-image relative flex">
                                <img src="{{ Storage::url(core()->getConfigData('sales.payment_methods.'.$key.'.image')) }}" alt="{{ $payment['title'] }}" class="w-full rounded-[5px]">
                                <div class="payment-overlay transition-all duration-[0.3s] ease-in-out rounded-[5px] w-full h-full absolute top-[0] left-[0]"></div>
                            </a>
                        </div>
                    </swiper-slide>

                @endif
            @endforeach
        </swiper>
    </div>
</div>


