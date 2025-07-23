<div class="flex flex-wrap w-full">
    <div class="w-full" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
        <!-- Desktop Toolbar Shimmer Effect -->
        <div class="flex flex-wrap w-full">
            <x-licious::shimmer.categories.toolbar />
        </div>

        <!-- Product Card Container -->
        @if(request()->query('mode') =='list')
            <div class="lex flex-wrap col-50 mb-[-24px] col-size">
                <x-licious::shimmer.products.cards.list count="8" />
            </div>
        @else
            <div class="flex flex-wrap col-50 mb-[-24px]">
                <!-- Product Card Shimmer Effect -->
                <x-licious::shimmer.products.cards.grid count="8" />
            </div>
        @endif
        <nav aria-label="..." class="cr-pagination mt-[24px] flex justify-center w-full">
            <button class="shimmer block w-[171.516px] h-12 mt-14 mx-auto py-3 rounded-2xl"></button>
        </nav>
    </div>
</div>
