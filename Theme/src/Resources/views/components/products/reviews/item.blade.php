
<v-product-review-item {{ $attributes }}></v-product-review-item>


@pushOnce('scripts')
    <!-- Product Review Item Template -->
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="flex gap-5 p-6 border border-[#e5e5e5] rounded-xl max-sm:flex-wrap max-xl:mb-5">
            <div>
                <img
                    v-if="review.profile"
                    class="flex justify-center items-center min-h-[100px] max-h-[100px] min-w-[100px] max-w-[100px] rounded-xl max-sm:hidden"
                    :src="review.profile"
                    :alt="review.name"
                    :title="review.name"
                >

                <div
                    v-else
                    class="flex justify-center items-center min-h-[100px] max-h-[100px] min-w-[100px] max-w-[100px] rounded-xl bg-[#F5F5F5] max-sm:hidden"
                    :title="review.name"
                >
                    <span
                        class="text-2xl text-[#6E6E6E] font-semibold"
                        v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"
                    >
                    </span>
                </div>
            </div>

            <div class="w-full">
                <div class="flex justify-between">
                    <p
                        class="text-xl font-medium max-sm:text-base"
                        v-text="review.name"
                    >
                    </p>

                    <div class="flex items-center">
                        <x-licious::products.star-rating
                            ::name="review.name"
                            ::value="review.rating"
                        />
                    </div>
                </div>

                <p
                    class="mt-2.5 text-sm font-medium max-sm:text-xs"
                    v-text="review.created_at"
                >
                </p>

                <p
                    class="mt-5 text-base text-[#6E6E6E] font-semibold max-sm:text-xs"
                    v-text="review.title"
                >
                </p>

                <p
                    class="mt-5 text-base text-[#6E6E6E] max-sm:text-xs"
                    v-text="review.comment"
                >
                </p>

                <button
                    class="secondary-button min-h-[34px] mt-2.5 px-2 py-1 rounded-lg text-sm"
                    @click="translate"
                >
                    <!-- Spinner -->
                    <template v-if="isLoading">
                        <img
                            class="animate-spin h-5 w-5 text-blue-600"
                            src="{{ bagisto_asset('images/spinner.svg') }}"
                        />

                        @lang('licious::app.products.view.reviews.translating')
                    </template>

                    <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" role="presentation"> <g clip-path="url(#clip0_3148_2242)"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12.1484 9.31989L9.31995 12.1483L19.9265 22.7549L22.755 19.9265L12.1484 9.31989ZM12.1484 10.7341L10.7342 12.1483L13.5626 14.9767L14.9768 13.5625L12.1484 10.7341Z" fill="#060C3B"/> <path d="M11.0877 3.30949L13.5625 4.44748L16.0374 3.30949L14.8994 5.78436L16.0374 8.25924L13.5625 7.12124L11.0877 8.25924L12.2257 5.78436L11.0877 3.30949Z" fill="#060C3B"/> <path d="M2.39219 2.39217L5.78438 3.95197L9.17656 2.39217L7.61677 5.78436L9.17656 9.17655L5.78438 7.61676L2.39219 9.17655L3.95198 5.78436L2.39219 2.39217Z" fill="#060C3B"/> <path d="M3.30947 11.0877L5.78434 12.2257L8.25922 11.0877L7.12122 13.5626L8.25922 16.0374L5.78434 14.8994L3.30947 16.0374L4.44746 13.5626L3.30947 11.0877Z" fill="#060C3B"/> </g> <defs> <clipPath id="clip0_3148_2242"> <rect width="24" height="24" fill="white"/> </clipPath> </defs> </svg>

                        @lang('licious::app.products.view.reviews.translate')
                    </template>
                </button>

                <!-- Review Attachments -->
                <div
                    class="flex gap-2 flex-wrap mt-3"
                    v-if="review.images.length"
                >
                    <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="h-12 w-12 flex"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >
                            <img
                                class="min-w-[50px] max-h-[50px] rounded-xl cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                        </a>

                        <a
                            :href="file.url"
                            class="flex h-12 w-12"
                            target="_blank"
                            v-else
                        >
                            <video
                                class="min-w-[50px] max-h-[50px] rounded-xl cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                            </video>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </script>

    <script type="module">

        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],

            data() {
                return {
                    isLoading: false,
                }
            },

            methods: {
                translate() {
                    this.isLoading = true;

                    this.$axios.get("{{ route('shop.api.products.reviews.translate', ['id' => $product->id, 'review_id' => ':reviewId']) }}".replace(':reviewId', this.review.id))
                        .then(response => {
                            this.isLoading = false;

                            this.review.comment = response.data.content;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
@endPushOnce
