@props(['count' => 0])

@for ($i = 0;  $i < $count; $i++)
    <div class="min-[992px]:w-[25%] w-[50%] max-[480px]:w-full px-[12px] cr-product-box mb-[24px]">
        <div class="cr-product-card h-full p-[12px] border-[1px] border-solid border-[#e9e9e9] bg-[#fff] rounded-[5px] overflow-hidden flex-col max-[480px]:w-full">

            <div class="shimmer relative w-full rounded">
                <div class="relative after:content-[' '] after:block after:pb-[calc(100%+9px)]"></div>
            </div>

            <div class="grid gap-2.5 content-start pt-[24px] text-center overflow-hidden max-[1199px]:pt-[20px]">
                <p class="shimmer w-3/4 h-6"></p>
                <p class="shimmer w-[55%] h-6"></p>

                <!-- Needs to implement that in future -->
                <div class="hidden flex gap-4 mt-3">
                    <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
                    <span class="shimmer w-[30px] h-[30px] block rounded-full"></span>
                </div>
            </div>
        </div>
    </div>
@endfor
