@props(['title' => 'Instagram Feed', 'subtitle' => 'Instagram Feed'])

<div class="flex flex-wrap">
    <div class="w-full px-[12px]">
        <div class="mb-[30px]">
            <div class="cr-banner mb-[15px] text-center">
                <h2 class="font-Manrope text-[32px] font-bold leading-[1.2] text-[#2b2b2d] max-[1199px]:text-[28px] max-[991px]:text-[25px] max-[767px]:text-[22px]">{{ $title }}</h2>
            </div>
            <div class="cr-banner-sub-title w-full">
                <p class="max-w-[600px] m-auto font-Poppins text-[14px] text-[#212529] leading-[22px] text-center max-[1199px]:w-[80%] max-[991px]:w-full font-Poppins text-[#7a7a7a]">{{ $subtitle }}</p>
            </div>
        </div>
    </div>
</div>
