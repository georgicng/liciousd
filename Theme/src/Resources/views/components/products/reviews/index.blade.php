{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-reviews :product-id="{{ $product->id }}">
    <div class="container max-1180:px-5">
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
        <div class="container max-1180:px-5">
            <!-- Create Review Form Container -->
            <div
                class="w-full"
                v-if="canReview"
            >
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
                                    :label="trans('shop::app.products.view.reviews.attachments')"
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
                            <x-licious::form.control-group>
                                <x-licious::form.control-group.label class="mt-0 required">
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
                                <x-licious::form.control-group>
                                    <x-licious::form.control-group.label class="required">
                                        @lang('licious::app.products.view.reviews.name')
                                    </x-licious::form.control-group.label>

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

                            <x-licious::form.control-group>
                                <x-licious::form.control-group.label class="required">
                                    @lang('licious::app.products.view.reviews.title')
                                </x-licious::form.control-group.label>

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

                            <x-licious::form.control-group>
                                <x-licious::form.control-group.label class="required">
                                    @lang('licious::app.products.view.reviews.comment')
                                </x-licious::form.control-group.label>

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
                            </x-licious::form.control-group>


                            <div class="flex gap-4 justify-start max-sm:flex-wrap mt-4 max-sm:justify-center max-sm:mb-5 max-xl:mb-5">
                                <button
                                    class="primary-button w-full max-w-[374px] py-4 px-11 rounded-2xl text-center"
                                    type='submit'
                                >
                                    @lang('licious::app.products.view.reviews.submit-review')
                                </button>

                                <button
                                    type="button"
                                    class="secondary-button items-center px-8 py-2.5 rounded-2xl max-sm:w-full max-sm:max-w-[374px]"
                                    @click="canReview = false"
                                >
                                    @lang('licious::app.products.view.reviews.cancel')
                                </button>
                            </div>
                        </div>
                    </form>
                </x-licious::form>
            </div>

            <!-- Product Reviews Container -->
            <div v-else>
                <!-- Review Container Shimmer Effect -->
                <template v-if="isLoading">
                    <x-licious::shimmer.products.reviews />
                </template>

                <template v-else>
                    <!-- Review Section Header -->
                    <div class="flex gap-4 items-center justify-between  max-sm:flex-wrap">
                        <h3 class="font-dmserif text-3xl max-sm:text-xl">
                            @lang('licious::app.products.view.reviews.customer-review')
                        </h3>

                        @if (
                            core()->getConfigData('catalog.products.review.guest_review')
                            || auth()->guard('customer')->user()
                        )
                            <div
                                class="flex gap-x-4 items-center px-4 py-2.5 border border-navyBlue rounded-xl cursor-pointer"
                                @click="canReview = true"
                            >
                                <span class="icon-pen text-2xl"></span>

                                @lang('licious::app.products.view.reviews.write-a-review')
                            </div>
                        @endif
                    </div>

                    <template v-if="reviews.length">
                        <!-- Average Rating Section -->
                        <div class="flex gap-4 justify-between items-center max-w-[365px] mt-8 max-sm:flex-wrap">
                            <p class="text-3xl font-medium max-sm:text-base">{{ number_format($avgRatings, 1) }}</p>

                            <x-licious::products.star-rating :value="$avgRatings" />

                            <p class="text-xs text-[#858585]">
                                (@{{ meta.total }} @lang('licious::app.products.view.reviews.customer-review'))
                            </p>
                        </div>

                        <!-- Ratings By Individual Stars -->
                        <div class="flex gap-x-5 items-center">
                            <div class="grid gap-y-5 flex-wrap max-w-[365px] mt-2.5">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="row grid grid-cols-[1fr_2fr] gap-2.5 items-center max-sm:flex-wrap">
                                        <div class="text-base font-medium">{{ $i }} Stars</div>

                                        <div class="h-4 w-[275px] max-w-full bg-[#E5E5E5] rounded-sm">
                                            <div class="h-4 bg-[#FEA82B] rounded-sm" style="width: {{ $percentageRatings[$i] }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="grid grid-cols-[1fr_1fr] gap-5 mt-14 max-1060:grid-cols-[1fr]">
                            <!-- Product Review Item Vue Component -->
                            <template v-for='review in reviews'>
                                <x-licious::products.review.item ::review="review" />
                            </template>
                        </div>

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
                        <div class="grid items-center justify-items-center w-full m-auto h-[476px] place-content-center text-center">
                            <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                            <p class="text-xl">
                                @lang('licious::app.products.view.reviews.empty-review')
                            </p>
                        </div>
                    </template>
                </template>
            </div>
        </div>
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
                        next: '{{ route('shop.api.products.reviews.index', $product->id) }}',
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
                    let selectedFiles = this.$refs.reviewImages.uploadedFiles.filter(obj => obj.file instanceof File).map(obj => obj.file);

                    params.attachments = { ...params.attachments, ...selectedFiles };

                    this.$axios.post('{{ route('shop.api.products.reviews.store', $product->id) }}', params, {
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
