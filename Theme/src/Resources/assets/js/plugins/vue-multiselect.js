import VueMultiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

export default {
    install: (app) => {
        app.component("multiselect", VueMultiselect);
    },
};
