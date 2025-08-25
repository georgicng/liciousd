@props(['title' => '', 'content' => 'Instagram Feed'])

<div class="cr-about h-full flex flex-col justify-center aos-init aos-animate" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
    @if($title)
        <h4 class="heading mb-[16px] font-Manrope text-[36px] font-bold leading-[46px] max-[1199px]:text-[28px] max-[1199px]:leading-[38px] max-[991px]:text-[25px] max-[991px]:leading-[35px] max-[767px]:text-[22px] max-[767px]:leading-[32px]">{{ $title }}</h4>
    @endif
    <div class="cr-about-content mt-[5px]">
        {!! $content !!}
    </div>
</div>
