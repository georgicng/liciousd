@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="content flex max-[575px]:flex-col">
        <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
            <div class="shimmer w-[100px] h-[100px] rounded-xl"></div>
        </div>


        <div class="details flex flex-col">
            <p class="shimmer w-[130px] h-[21px]"></p>
            <p class="shimmer w-[130px] h-[21px]"></p>
        </div>

        <div class="cr-t-review-rating ml-auto mb-[20px] max-[575px]:ml-[0] max-[575px]:mb-[24px]">
            <span class="shimmer rounded-xl w-12 h-12"></span>
            <span class="shimmer rounded-xl w-12 h-12"></span>
            <span class="shimmer rounded-xl w-12 h-12"></span>
            <span class="shimmer rounded-xl w-12 h-12"></span>
            <span class="shimmer rounded-xl w-12 h-12"></span>
            <span class="shimmer rounded-xl w-12 h-12"></span>
        </div>
    </div>
@endfor
