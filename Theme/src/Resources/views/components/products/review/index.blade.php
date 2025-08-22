@props(['product', 'avgRatings', 'percentageRatings'])

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-reviews :product-id="{{ $product->id }}">
    <div class="post mb-[30px]">
        <x-licious::shimmer.products.reviews />
    </div>
</v-product-reviews>

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <!-- Product Review Template -->
    <script
        type="text/x-template"
        id="v-product-reviews-template"
    >
        <!-- Review Container Shimmer Effect -->
        <template v-if="isLoading">
            <x-licious::shimmer.products.reviews />
        </template>
        <template v-else>
            <!-- Create Review Form Container -->
            <div class="post p-[30px]" v-if="canReview" >
                <h4 class="heading font-Poppins text-[16px] font-medium leading-[1.5] text-[#2b2b2d] pb-[10px] mb-[0.5rem] ">Add a Review</h4>

                <x-licious::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Review Form -->
                    <form
                        class="grid grid-cols-[auto_1fr] gap-10 justify-center max-md:grid-cols-[1fr]"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-licious::form.control-group>
                                <x-licious::form.control-group.control
                                    type="image"
                                    class="!p-0 !mb-0"
                                    name="attachments"
                                    :label="trans('licious::app.products.view.reviews.attachments')"
                                    :is-multiple="true"
                                    ref="reviewImages"
                                />

                                <x-licious::form.control-group.error
                                    class="mt-4"
                                    control-name="attachments"
                                />
                            </x-licious::form.control-group>
                        </div>


                        <div>
                            <x-licious::form.control-group class="cr-ratting-star flex">
                                <x-licious::form.control-group.label class="font-Poppins text-[14px] text-[#7a7a7a] leading-[1.75] mr-[10px]">
                                    @lang('licious::app.products.view.reviews.rating')
                                </x-licious::form.control-group.label>

                                <x-licious::products.star-rating
                                    name="rating"
                                    rules="required"
                                    :value="old('rating') ?? 5"
                                    :label="trans('licious::app.products.view.reviews.rating')"
                                    :disabled="false"
                                />

                                <x-licious::form.control-group.error control-name="rating" />
                            </x-licious::form.control-group>

                            @if (
                                core()->getConfigData('catalog.products.review.guest_review')
                                && ! auth()->guard('customer')->user()
                            )
                                <x-licious::form.control-group class="cr-ratting-input mb-[10px]">
                                    <x-licious::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :value="old('name')"
                                        :label="trans('licious::app.products.view.reviews.name')"
                                        :placeholder="trans('licious::app.products.view.reviews.name')"
                                        class="w-full h-[50px] py-[5px] px-[20px] outline-[0] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] twxt-[#777] text-[14px]"
                                    />

                                    <x-licious::form.control-group.error control-name="name" />
                                </x-licious::form.control-group>
                            @endif

                            <x-licious::form.control-group class="cr-ratting-input mb-[10px]">
                                <x-licious::form.control-group.control
                                    type="text"
                                    name="title"
                                    rules="required"
                                    :value="old('title')"
                                    :label="trans('licious::app.products.view.reviews.title')"
                                    :placeholder="trans('licious::app.products.view.reviews.title')"
                                    class="w-full h-[50px] py-[5px] px-[20px] outline-[0] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] twxt-[#777] text-[14px]"
                                />

                                <x-licious::form.control-group.error control-name="title" />
                            </x-licious::form.control-group>

                            <x-licious::form.control-group class="cr-ratting-input mb-[10px]">
                                <x-licious::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    :value="old('comment')"
                                    :label="trans('licious::app.products.view.reviews.comment')"
                                    :placeholder="trans('licious::app.products.view.reviews.comment')"
                                    class="w-full h-[150px] mb-[15px] p-[20px] bg-transparent text-[14px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-[#777] outline-[0]"
                                />
                                <x-licious::form.control-group.error control-name="comment" />
                            </x-licious::form.control-group>

                            <div class="cr-ratting-input form-submit flex gap-4">
                                <button
                                    class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] bg-[#64b496] text-[#fff] border-[1px] border-solid border-[#64b496] rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000]"
                                    type='submit'
                                >
                                    @lang('licious::app.products.view.reviews.submit-review')
                                </button>
                                <button
                                    class="cr-button h-[40px] font-bold transition-all duration-[0.3s] ease-in-out py-[8px] px-[22px] text-[14px] font-Manrope capitalize leading-[1.2] border-[1px] border-solid  border-navyBlue rounded-[5px] flex items-center justify-center hover:bg-[#000] hover:border-[#000] hover:text-[#fff]"
                                    type='button'
                                    @click="canReview = false"
                                >
                                    @lang('licious::app.products.view.reviews.cancel')
                                </button>
                            </div>
                        </div>
                    </form>
                </x-licious::form>
            </div>

            <div v-else class="post p-[30px]">
                <template v-if="reviews.length">
                    <!-- Product Review Item Vue Component -->
                    <template v-for='review in reviews'>
                        <x-licious::products.review.item ::review="review" :$product />
                    </template>

                    <button
                        class="block mx-auto w-max mt-14 py-3 px-11 bg-white border border-navyBlue rounded-2xl text-center text-navyBlue text-base font-medium"
                        v-if="links?.next"
                        @click="get()"
                    >
                        @lang('licious::app.products.view.reviews.load-more')
                    </button>
                </template>
                <template v-else>
                    <!-- Empty Review Section -->
                    <div class="flex flex-col items-center justify-center mt-[30px] mb-[30px]">
                        <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                        <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                            @lang('licious::app.products.view.reviews.empty-review')
                        </p>
                    </div>
                </template>

                @if (
                    core()->getConfigData('catalog.products.review.guest_review')
                    || auth()->guard('customer')->user()
                )
                    <div class="flex gap-4 items-center justify-center max-sm:flex-wrap">
                        <div
                            class="flex gap-x-4 items-center px-4 py-2.5 border border-navyBlue rounded-xl cursor-pointer"
                            @click="canReview = true"
                        >
                            <span class="ri-edit-line text-2xl"></span>

                            @lang('licious::app.products.view.reviews.write-a-review')
                        </div>
                    </div>
                @endif
            </div>

        </template>
    </script>

    <script type="module">
        app.component('v-product-reviews', {
            template: '#v-product-reviews-template',

            props: ['productId'],

            data() {
                return {
                    isLoading: true,

                    canReview: false,

                    reviews: [],

                    links: {
                        next: "{{ route('shop.api.products.reviews.index', $product->id) }}",
                    },

                    meta: {},
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    if (this.links?.next) {
                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.isLoading = false;

                                this.reviews = [...this.reviews, ...response.data.data];

                                this.links = response.data.links;

                                this.meta = response.data.meta;
                            })
                            .catch(error => {});
                    }
                },

                store(params, { resetForm, setErrors }) {
                    this.$axios.post("{{ route('shop.api.products.reviews.store', $product->id) }}", params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            resetForm();

                            this.canReview = false;
                        })
                        .catch(error => {
                            setErrors({'attachments': ["@lang('licious::app.products.view.reviews.failed-to-upload')"]});

                            this.$refs.reviewImages.uploadedFiles.forEach(element => {
                                setTimeout(() => {
                                    this.$refs.reviewImages.removeFile();
                                }, 0);
                            });
                        });
                },

                selectReviewImage() {
                    this.reviewImage = event.target.files[0];
                },
            },
        });
    </script>
@endPushOnce
