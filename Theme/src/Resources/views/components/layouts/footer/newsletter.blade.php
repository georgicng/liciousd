<h4 class="cr-sub-title font-Manrope relative text-[18px] font-bold leading-[1.3] text-[#000] mb-[15px] max-[991px]:py-[15px] max-[991px]:mb-[0] max-[991px]:text-[15px] max-[991px]:border-b-[1px] max-[991px]:border-solid max-[991px]:border-[#e9e9e9]">
    Subscribe Our Newsletter
    <span class="cr-heading-res hidden"></span>
</h4>
<div class="cr-footer-links max-[991px]:hidden cr-footer-dropdown max-[1199px]:max-w-[500px] max-[991px]:mt-[24px]">
    <x-shop::form
        :action="route('shop.subscription.store')"
        class="cr-search-footer relative"
    >
        <x-shop::form.control-group.control
            type="email"
            class="search-input w-full h-[44px] py-[5px] px-[15px] border-[1px] border-solid border-[#e9e9e9] outline-[0] rounded-[5px]"
            name="email"
            rules="required|email"
            :aria-label="trans('shop::app.components.layouts.footer.email')"
            placeholder="email@example.com"
        />

        <x-shop::form.control-group.error control-name="email" />
        <button
            type="submit" class="search-btn w-[50px] absolute right-[0] top-[0] bottom-[0] flex items-center justify-center">
            <i class="ri-send-plane-fill text-[21px] text-[#000]"></i>
        </button>
    </x-shop::form>
</div>
