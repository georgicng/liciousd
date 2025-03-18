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
        <div class="post mb-[30px]">
            <!-- Review Container Shimmer Effect -->
            <template v-if="isLoading">
                <x-licious::shimmer.products.reviews />
            </template>

            <template v-else>
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
                    <div class="mt-[30px] mb-[30px]">
                        <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                        <p class="text-[#6E6E6E] text-lg max-1180:text-sm">
                            @lang('licious::app.products.view.reviews.empty-review')
                        </p>
                    </div>
                </template>
                <!-- Create Review Form Container -->
                <template v-if="canReview" >
                    <h4 class="heading font-Poppins text-[16px] font-medium leading-[1.5] text-[#2b2b2d] pb-[10px] mb-[0.5rem] ">Add a Review</h4>

                    <x-licious::form
                        v-slot="{ meta, errors, handleSubmit }"
                        as="div"
                    >
                        <!-- Review Form -->
                        <form
                            @submit="handleSubmit($event, store)"
                            enctype="multipart/form-data"
                        >
                            <x-licious::form.control-group class="cr-ratting-star flex">
                                <x-licious::form.control-group.label class="font-Poppins text-[14px] text-[#7a7a7a] leading-[1.75] mr-[10px]">
                                    @lang('licious::app.products.view.reviews.rating')
                                </x-licious::form.control-group.label>

                                <x-licious::products.star-rating
                                    name="rating"
                                    rules="required"
                                    :value="old('rating') ?? 5"
                                    :label="trans('shop::app.products.view.reviews.rating')"
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
                                        :label="trans('shop::app.products.view.reviews.name')"
                                        :placeholder="trans('shop::app.products.view.reviews.name')"
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
                                    :label="trans('shop::app.products.view.reviews.title')"
                                    :placeholder="trans('shop::app.products.view.reviews.title')"
                                />

                                <x-licious::form.control-group.error control-name="title" />
                            </x-licious::form.control-group>

                            <div class="cr-ratting-input form-submit">

                                <x-licious::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    :value="old('comment')"
                                    :label="trans('shop::app.products.view.reviews.comment')"
                                    :placeholder="trans('shop::app.products.view.reviews.comment')"
                                    rows="12"
                                />
                                <x-licious::form.control-group.error control-name="comment" />

                                <button
                                    class="primary-button w-full max-w-[374px] py-4 px-11 rounded-2xl text-center"
                                    type='submit'
                                >
                                    @lang('licious::app.products.view.reviews.submit-review')
                                </button>
                            </div>
                        </form>
                    </x-licious::form>
                </template>
            </template>
        </div>

    </script>

    <script type="module">
        app.component('v-product-reviews', {
            template: '#v-product-reviews-template',

            props: ['productId'],

            data() {
                return {
                    isLoading: true,

                    canReview: true,

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
                            setErrors({'attachments': ["@lang('shop::app.products.view.reviews.failed-to-upload')"]});

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
