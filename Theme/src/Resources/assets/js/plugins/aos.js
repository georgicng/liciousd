import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles

export default {
    install(app) {
        app.config.globalProperties.$aos = AOS;
    },
};
