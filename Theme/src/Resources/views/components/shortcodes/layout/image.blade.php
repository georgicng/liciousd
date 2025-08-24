@props(['image' => '#'])

<section class="section-faq py-[100px] max-[1199px]:py-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
            <div class="w-full flex flex-wrap mb-[-30px]">
                <div class="min-[992px]:w-[50%] w-full px-[12px] mb-[30px]">
                    <div class="cr-faq-img">
                        <img src="{{ $image }}" alt="about" class="w-full rounded-[5px]">
                    </div>
                </div>
                <div class="min-[992px]:w-[50%] w-full px-[12px] mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                    {!! $slot !!}
                </div>
            </div>
        </div>
    </div>
</section>

