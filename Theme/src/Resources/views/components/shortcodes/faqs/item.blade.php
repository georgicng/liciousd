@props(['question' => 'Instagram Feed', 'answer' => 'Instagram Feed'])

<div class="cr-accordion-item border-[#eee] overflow-hidden mb-[10px] border-[1px] border-solid border-[#eee] rounded-[5px]">
    <h4 class="accordion-head active-arrow m-[0] p-[14px] text-[#4b5966] text-[16px] leading-[20px] font-medium relative border-b-[1px] border-solid border-[#eee] font-Poppins cursor-pointer tracking-[0] max-[767px]:text-[15px]">
        {{ $question }}
    </h4>
    <div class="accordion-body py-[15px] p-[15px]">
        <p class="text-[14px] font-Poppins text-[#7a7a7a] leading-[1.75]">{!! $answer !!}</p>
    </div>
</div>
