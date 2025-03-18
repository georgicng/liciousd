{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="cr-fix" id="cr-main-menu-desk">
    <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
        <div class="cr-menu-list w-full px-[12px] relative flex items-center flex-row justify-between">
            <x-licious::layouts.header.menu.sub />

            <nav class="justify-between relative flex flex-wrap items-center max-[991px]:w-full max-[991px]:py-[10px]">

                <x-licious::drawer.category />

                <div class="cr-header-buttons hidden max-[991px]:flex max-[991px]:items-center">
                    <x-licious::layouts.header.menu.account-toggle />

                    <a href="wishlist.html" class="cr-right-bar-item transition-all duration-[0.3s] ease-in-out mr-[16px] max-[991px]:mr-[20px]">
                        <i class="ri-heart-line text-[20px]"></i>
                    </a>

                    <!-- Mini cart -->
                    <x-licious::drawer.cart :type="'mobile'" />
                </div>



                <div class="min-[992px]:flex min-[992px]:basis-auto grow-[1] items-center hidden" id="navbarSupportedContent">
                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

                    <x-licious::layouts.header.menu.desktop />

                    {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
                </div>
            </nav>

            <div class="cr-calling flex justify-end items-center max-[1199px]:hidden">
                <i class="ri-phone-line pr-[5px] text-[20px]"></i>
                <a href="javascript:void(0)" class="text-[15px] font-medium">+123 ( 456 ) ( 7890 )</a>
            </div>
        </div>
    </div>
</div>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
