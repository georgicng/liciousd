@props(['subject' => '', 'content' => ''])

<div class="w-full cr-cgi-block mb-[24px]">
    <div class="cr-cgi-block-inner">
        @if($subject)
            <h5 class="cr-cgi-block-title mb-[10px] text-[18px] font-bold leading-[1.2]">{{ $subject }}</h5>
        @endif
        <p class="leading-[28px] mb-[0] text-[14px] font-Poppins text-[#7a7a7a]">{!! $content !!}</p>
    </div>
</div>
