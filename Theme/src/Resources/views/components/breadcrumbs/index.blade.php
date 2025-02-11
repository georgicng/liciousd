@props([
    'name'  => '',
    'entity' => null,
])
<section class="section-breadcrumb">
    <div class="cr-breadcrumb-image w-full h-[70px] bg-[#e4f2ed] z-[0] relative flex items-center max-[575px]:h-[100px]">
        <div class="container min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px] w-full m-auto">
            <div class="flex flex-wrap w-full">
                <div class="w-full px-[12px]">
                    <div class="cr-breadcrumb-title flex items-center justify-between flex-row max-[575px]:flex-col">
                        <h2 class="mb-[0] font-Manrope text-[19px] leading-[1] font-bold text-[#2b2b2d] max-[1199px]:text-[18px] max-[767px]:text-[17px] max-[575px]:mb-[5px] max-[575px]:text-[20px]">{{ $name }}</h2>
                        {{ Breadcrumbs::view('shop::partials.breadcrumbs', $name, $entity) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
