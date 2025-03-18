<v-product-gallery ref="gallery">
    <x-licious::shimmer.products.gallery />
</v-product-gallery>


@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-gallery-template"
    >
        <div class="vehicle-detail-banner banner-content clearfix h-full">
            <div class="banner-slider sticky top-[30px]">
                <Carousel ref="main" v-bind="settings.main" class="slider slider-for mb-[15px]">
                    <div v-for="(image, index) in media.images" class="slider-banner-image">
                        <zoomer
                            :regular="image.large_image_url"
                            :zoom="image.large_image_url"
                            :zoom-amount="3"
                            img-class="img-fluid"
                            alt="{{ $product->name }}">
                            <div class="zoom-image-hover h-full flex items-center text-center border-[1px] border-solid border-[#e9e9e9] bg-[#f7f7f8] rounded-[5px] cursor-pointer">
                                <img :src="image.large_image_url" :alt="`{{ $product->name }}-${index}`" class="product-image w-full block m-auto">
                            </div>
                        </zoomer>
                    </div>

                </Carousel>
                <Carousel ref="nav" v-bind="settings.nav" class="slider slider-nav thumb-image mx-[-6px]">
                    <div v-for="(image, index) in media.images" class="thumbnail-image">
                        <div class="thumbImg mx-[6px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] flex justify-center items-center">
                            <img :src="image.small_image_url" :alt="`{{ $product->name }}-${index}`" class="w-full p-[2px] rounded-[5px]">
                        </div>
                    </div>
                </Carousel>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-gallery', {
            template: '#v-product-gallery-template',
            setup() {

            },

            data() {
                return {
                    media: {
                        images: @json(product_image()->getGalleryImages($product)),
                    },
                    c1: null,
                    c2: null,
                    settings: { main: {}, nav: {} }
                }
            },
            mounted() {
                const c1 = this.$refs.main;
                const c2 = this.$refs.nav;
                this.settings.main = {
                    dots: false,
                    infinite: true,
                    groupsToShow: 1,
                    groupsToScroll: 1,
                    slidesPerGroup: 1,
                    swipe: true,
                    focusOnSelect: true,
                    fade: true,
                    asNavFor: c2
                };
                this.settings.nav ={
                    dots: false,
                    infinite: true,
                    groupsToShow: 5,
                    groupsToScroll: 1,
                    slidesPerGroup: 1,
                    swipe: true,
                    focusOnSelect: true,
                    asNavFor: c1,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                groupsToShow: 4,
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                groupsToShow: 5,
                            }
                        },
                        {
                            breakpoint: 420,
                            settings: {
                                groupsToShow: 4,
                            }
                        }
                    ]
                };
            },
        })
    </script>
@endpushOnce
