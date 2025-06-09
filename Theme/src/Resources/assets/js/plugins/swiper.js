  // Import Swiper Vue.js components
  import { Swiper, SwiperSlide } from 'swiper/vue';

  // Import Swiper styles
  import 'swiper/css';

export default {
    install(app) {
        app.component("Swiper", Swiper);
        app.component("SwiperSlide", SwiperSlide);
    },
};
