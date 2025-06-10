@if ($prices['final']['price'] < $prices['regular']['price'])
    <span
        class="new-price font-Poppins text-[24px] font-semibold leading-[1.167] text-[#64b496] max-[767px]:text-[22px] max-[575px]:text-[20px]"
        aria-label="{{ $prices['regular']['formatted_price'] }}"
    >
        {{ $prices['regular']['formatted_price'] }}
</span>

    <span class="old-price font-Poppins text-[16px] line-through leading-[1.75] text-[#7a7a7a]">
        {{ $prices['final']['formatted_price'] }}
    </span>
@else
    <span class="new-price font-Poppins text-[24px] font-semibold leading-[1.167] text-[#64b496] max-[767px]:text-[22px] max-[575px]:text-[20px]">
        {{ $prices['regular']['formatted_price'] }}
    </span>
@endif
