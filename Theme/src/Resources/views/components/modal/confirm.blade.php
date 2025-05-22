<v-modal-confirm ref="confirmModal"></v-modal-confirm>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-modal-confirm-template"
    >
        <div>
            <transition
                tag="div"
                name="modal-overlay"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    class="cr-modal-overlay w-full h-screen fixed top-0 left-0 z-[10] bg-[#000000b3]"
                    v-show="isOpen"
                ></div>
            </transition>

            <transition
                tag="div"
                name="modal-content"
                enter-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
                enter-to-class="opacity-100 translate-y-0 md:scale-100"
                leave-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 md:scale-100"
                leave-to-class="opacity-0 translate-y-4 md:translate-y-0 md:scale-95"
            >
                <div
                    class="cr-modal max-[575px]:w-full fixed top-[50%] left-[50%] z-[30] max-[767px]:w-full max-[767px]:max-h-full max-[767px]:overflow-y-auto transition" v-show="isOpen"
                >
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="cr-modal-dialog max-w-[475px] max-md:w-[90%] transition-transform duration-[0.3s] ease-out cr-fadeOutUp">
                            <div class="modal-content p-[30px] relative bg-[#fff] rounded-xl">
                                <div class="flex gap-2.5">
                                    <div>
                                        <span class="flex p-2.5 border border-[rgba(6,12,59,0.20)] rounded-full">
                                            <i class="ri-alert-line text-3xl"></i>
                                        </span>
                                    </div>

                                    <div>
                                        <div class="cr-size-and-weight-contain pb-[20px] max-[767px]:mt-[24px]">
                                            <h2 class="heading mb-[15px] block text-left text-[#2b2b2d] text-[22px] leading-[1.5] font-medium max-[1399px]:text-[26px] max-[991px]:text-[20px]">
                                                @{{ title }}
                                            </h2>

                                            <p class="mb-[0] font-Poppins text-[#7a7a7a] text-[14px] leading-[1.75]">
                                                @{{ message }}
                                            </p>
                                        </div>

                                        <div class="flex gap-2.5 justify-end">
                                            <button type="button" class="btn m-[10px] rounded-[5px] transition-all duration-[0.3s] ease-in-out h-[45px] p-[0] px-[25px] border-[0] text-[14px] font-medium leading-[45px] uppercase" @click="disagree">
                                                @{{ options.btnDisagree }}
                                            </button>

                                            <button type="button" class="btn btn-success m-[10px] rounded-[5px] transition-all duration-[0.3s] ease-in-out h-[45px] p-[0] px-[25px] border-[0] text-[14px] font-medium leading-[45px] uppercase bg-[#64b496] text-[#fff]" @click="agree">
                                                @{{ options.btnAgree }} 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </script>

    <script type="module">
        app.component('v-modal-confirm', {
            template: '#v-modal-confirm-template',

            data() {
                return {
                    isOpen: false,

                    title: '',

                    message: '',

                    options: {
                        btnDisagree: '',
                        btnAgree: '',
                    },

                    agreeCallback: null,

                    disagreeCallback: null,
                };
            },

            created() {
                this.registerGlobalEvents();
            },

            methods: {
                open({
                    title = "@lang('shop::app.components.modal.confirm.title')",
                    message = "@lang('shop::app.components.modal.confirm.message')",
                    options = {
                        btnDisagree: "@lang('shop::app.components.modal.confirm.disagree-btn')",
                        btnAgree: "@lang('shop::app.components.modal.confirm.agree-btn')",
                    },
                    agree = () => {},
                    disagree = () => {},
                }) {
                    this.isOpen = true;

                    document.body.style.overflow = 'hidden';

                    this.title = title;

                    this.message = message;

                    this.options = options;

                    this.agreeCallback = agree;

                    this.disagreeCallback = disagree;
                },

                disagree() {
                    this.isOpen = false;

                    document.body.style.overflow = 'auto';

                    this.disagreeCallback();
                },

                agree() {
                    this.isOpen = false;

                    document.body.style.overflow = 'auto';

                    this.agreeCallback();
                },

                registerGlobalEvents() {
                    this.$emitter.on('open-confirm-modal', this.open);
                },
            }
        });
    </script>
@endPushOnce
