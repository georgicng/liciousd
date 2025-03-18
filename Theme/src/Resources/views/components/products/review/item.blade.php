
@props(['product'])

<v-product-review-item {{ $attributes }}></v-product-review-item>

@pushOnce('scripts')
    <!-- Product Review Item Template -->
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="content flex max-[575px]:flex-col">
            <img 
                v-if="review.profile"
                :src="review.profile"
                :alt="review.name"
                :title="review.name"
                class="h-[50px] w-[50px] mr-[24px] rounded-[5px] max-[575px]:mb-[24px]"
            >
            <div class="details flex flex-col">
                <span class="date mb-[10px] text-[13px] text-[#777]" v-text="review.created_at"></span>
                <span class="name mb-[10px] font-medium text-[17px]" v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"></span>
            </div>
            <div class="cr-t-review-rating ml-auto mb-[20px] max-[575px]:ml-[0] max-[575px]:mb-[24px]">
                <i v-for="i in availableRating" class="ri-star-s-fill text-[19px] tracking-[-5px]" :class="{ 'text-[#f5885f]' : i >= max  }"></i>
            </div>
        </div>
        <p class="m-[0] font-Poppins text-[14px] text-[#7a7a7a] leading-[1.75] pl-[74px] max-[575px]:p-[0]" v-text="review.comment"></p>
    </script>

    <script type="module">

        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],

            data() {
                return {
                    isLoading: false,
                    availableRatings: [1,2,3,4,5],
                }
            },
            computed: {
                max() {
                    return this.availableRatings[a.length.length - 1]
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
