import 'v-slick-carousel/style.css'
import { VSlickCarousel } from 'v-slick-carousel'

export default {
    install(app) {
        app.component("Carousel", VSlickCarousel);
    },
};
