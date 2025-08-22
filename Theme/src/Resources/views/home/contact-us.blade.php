<!-- Page Layout -->
<x-licious::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('licious::app.home.contact.title')
    </x-slot>

    <!-- Breadcrumb -->
    {!! view_render_event('bagisto.shop.home.contact.breadcrumbs.before') !!}

        <x-licious::breadcrumbs name="contact" />

    {!! view_render_event('bagisto.shop.home.contact.breadcrumbs.after') !!}

     <!-- Contact -->
    <section class="section-Contact py-[100px] max-[1199px]:py-[70px]">
        <div class="flex flex-wrap justify-between relative items-center mx-auto min-[1600px]:max-w-[1500px] min-[1400px]:max-w-[1320px] min-[1200px]:max-w-[1140px] min-[992px]:max-w-[960px] min-[768px]:max-w-[720px] min-[576px]:max-w-[540px]">
            <div class="flex flex-wrap w-full">
                <div class="w-full px-[12px]">
                    <div class="mb-[30px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                        <div class="cr-banner mb-[15px] text-center">
                            <h2 class="font-Manrope text-[32px] font-bold leading-[1.2] text-[#2b2b2d] max-[1199px]:text-[28px] max-[991px]:text-[25px] max-[767px]:text-[22px]"> @lang('licious::app.home.contact.title')</h2>
                        </div>
                        <div class="cr-banner-sub-title w-full">
                            <p class="max-w-[600px] m-auto font-Poppins text-[14px] text-[#212529] leading-[22px] text-center max-[1199px]:w-[80%] max-[991px]:w-full">@lang('licious::app.home.contact.about')</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap w-full mb-[-24px]">
                <div class="min-[992px]:w-[33.33%] min-[768px]:w-[50%] w-full px-[12px] mb-[24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                    <div class="cr-info-box p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-center max-[767px]:max-w-[350px] max-[767px]:m-auto max-[575px]:max-w-[300px]">
                        <div class="cr-icon">
                            <i class="ri-phone-line text-[30px] text-[#64b496]"></i>
                        </div>
                        <div class="cr-info-content">
                            <h4 class="heading font-Manrope text-[18px] font-bold text-[#2b2b2d] leading-[1.2] mb-[0.5rem] max-[991px]:text-[22px]">Contact</h4>
                            <p class="font-Poppins leading-[1.75] text-[14px] max-[991px]:text-[13px] m-[0] text-[#777]"><a href="javascript:void(0)" class="text-[#000]"><i class="ri-phone-line"></i> &nbsp; (+91)-9876XXXXX</a></p>
                            <p class="font-Poppins leading-[1.75] text-[14px] max-[991px]:text-[13px] m-[0] text-[#777]"><a href="javascript:void(0)" class="text-[#000]"><i class="ri-phone-line"></i> &nbsp; (+91)-987654XXXX</a></p>
                        </div>
                    </div>
                </div>
                <div class="min-[992px]:w-[33.33%] min-[768px]:w-[50%] w-full px-[12px] mb-[24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="600">
                    <div class="cr-info-box p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-center max-[767px]:max-w-[350px] max-[767px]:m-auto max-[575px]:max-w-[300px]">
                        <div class="cr-icon">
                            <i class="ri-mail-line text-[30px] text-[#64b496]"></i>
                        </div>
                        <div class="cr-info-content">
                            <h4 class="heading font-Manrope text-[18px] font-bold text-[#2b2b2d] leading-[1.2] mb-[0.5rem] max-[991px]:text-[22px]">Mail & Website</h4>
                            <p class="font-Poppins leading-[1.75] text-[14px] max-[991px]:text-[13px] m-[0] text-[#777]"><a href="javascript:void(0)" class="text-[#000]"><i class="ri-mail-line"></i> &nbsp; mail.example@gmail.com</a></p>
                            <p class="font-Poppins leading-[1.75] text-[14px] max-[991px]:text-[13px] m-[0] text-[#777]"><a href="javascript:void(0)" class="text-[#000]"><i class="ri-globe-line"></i> &nbsp; www.yourdomain.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="min-[992px]:w-[33.33%] w-full px-[12px] mb-[24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="800">
                    <div class="cr-info-box p-[24px] bg-[#fff] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-center max-[767px]:max-w-[350px] max-[767px]:m-auto max-[575px]:max-w-[300px]">
                        <div class="cr-icon">
                            <i class="ri-map-pin-line text-[30px] text-[#64b496]"></i>
                        </div>
                        <div class="cr-info-content">
                            <h4 class="heading font-Manrope text-[18px] font-bold text-[#2b2b2d] leading-[1.2] mb-[0.5rem] max-[991px]:text-[22px]">Address</h4>
                            <p class="font-Poppins leading-[1.75] text-[14px] max-[991px]:text-[13px] m-[0] text-[#777]"><a href="javascript:void(0)" class="text-[#000]"><i class="ri-map-pin-line"></i> &nbsp; 140 Ruami Moraes Filho, 987 - Salvador - MA, 40352, Brazil.</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap w-full pt-[100px] max-[1199px]:pt-[70px]  mb-[-24px]">
                <div class="min-[768px]:w-[50%] w-full px-[12px] mb-[24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="400">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2965.0824050173574!2d-93.63905729999999!3d41.998507000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sWebFilings%2C+University+Boulevard%2C+Ames%2C+IA!5e0!3m2!1sen!2sus!4v1390839289319"
                        title="maps" class="w-full h-full mb-[-7px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px]">
                    </iframe>
                </div>
                <div class="min-[768px]:w-[50%] w-full px-[12px] mb-[24px]" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="800">
                    <x-licious::form :action="route('shop.home.contact_us.send_mail')" class="cr-content-form">
                        <x-licious::form.control-group class="form-group mb-[24px]">
                            <x-licious::form.control-group.control
                                type="text"
                                class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                                name="name"
                                rules="required"
                                :value="old('name')"
                                :label="trans('licious::app.home.contact.name')"
                                :placeholder="trans('licious::app.home.contact.name')"
                                :aria-label="trans('licious::app.home.contact.name')"
                                aria-required="true"
                                class="cr-form-control w-full py-[0.575rem] px-[0.75rem] block text-[16px] font-normal leading-[1.5] text-[#000] border-[1px] border-solid border-[#e9e9e9] outline-[0] appearance-none rounded-[5px]"
                            />
                            <x-licious::form.control-group.error control-name="name" />
                        </x-licious::form.control-group>

                        <x-licious::form.control-group class="form-group mb-[24px]">
                            <x-licious::form.control-group.control
                                type="email"
                                class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('licious::app.home.contact.email')"
                                :placeholder="trans('licious::app.home.contact.email')"
                                :aria-label="trans('licious::app.home.contact.email')"
                                aria-required="true"
                                class="cr-form-control w-full py-[0.575rem] px-[0.75rem] block text-[16px] font-normal leading-[1.5] text-[#000] border-[1px] border-solid border-[#e9e9e9] outline-[0] appearance-none rounded-[5px]"
                            />

                            <x-licious::form.control-group.error control-name="email" />
                        </x-licious::form.control-group>

                        <x-licious::form.control-group class="form-group mb-[24px]">
                            <x-licious::form.control-group.control
                                type="text"
                                class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                                name="contact"
                                rules="phone"
                                :value="old('contact')"
                                :label="trans('licious::app.home.contact.phone-number')"
                                :placeholder="trans('licious::app.home.contact.phone-number')"
                                :aria-label="trans('licious::app.home.contact.phone-number')"
                                class="cr-form-control w-full py-[0.575rem] px-[0.75rem] block text-[16px] font-normal leading-[1.5] text-[#000] border-[1px] border-solid border-[#e9e9e9] outline-[0] appearance-none rounded-[5px]"
                            />

                            <x-licious::form.control-group.error control-name="contact" />
                        </x-licious::form.control-group>

                        <x-licious::form.control-group class="form-group mb-[24px]">
                            <x-licious::form.control-group.control
                                type="textarea"
                                class="px-6 py-5 max-md:py-3 max-sm:py-3.5"
                                name="message"
                                rules="required"
                                :label="trans('licious::app.home.contact.message')"
                                :placeholder="trans('licious::app.home.contact.describe-here')"
                                :aria-label="trans('licious::app.home.contact.message')"
                                aria-required="true"
                                rows="4"
                                class="cr-form-control w-full py-[0.575rem] px-[0.75rem] block text-[16px] font-normal leading-[1.5] text-[#000] border-[1px] border-solid border-[#e9e9e9] outline-[0] appearance-none rounded-[5px]"
                            />
                            <x-licious::form.control-group.error control-name="message" />
                        </x-licious::form.control-group>

                        <!-- Re captcha -->
                        @if (core()->getConfigData('customer.captcha.credentials.status'))
                            <div class="mb-5 flex">
                                {!! Captcha::render() !!}
                            </div>
                        @endif

                        <button type="submit" class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]">@lang('licious::app.home.contact.submit')</button>
                    </x-licious::form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        {!! Captcha::renderJS() !!}
    @endpush
</x-licious::layouts>
