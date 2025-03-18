import { VueImageZoomer } from 'vue-image-zoomer'
import 'vue-image-zoomer/dist/style.css';

export default {
    install(app) {
        app.component("Zoomer", VueImageZoomer);
    },
};
