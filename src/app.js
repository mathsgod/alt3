/*import Vue from 'vue';
import VueResource from 'vue-resource';
Vue.use(VueResource);*/

import Alert from './alert.js';
class APP {
    constructor() {
        this.alert = new Alert();
    }

    async mutation(query) {
        var resp = await Vue.gql.mutation("api", query);
        resp = resp.data;
        if (resp.error) throw resp.error.message;
        return resp;
    }

    async query(query) {
        var resp = await Vue.gql.query("api", query);
        resp = resp.data;
        if (resp.error) throw resp.error.message;
        return resp;
    }

    async updateMyInfo(data) {
        return await this.mutation({
            updateMyInfo: {
                __args: { data }
            }
        });
    }
}

var app = new APP();
window.app = app;

//* reg to vue
window.Vue.use({
    install: (Vue, options) => {
        Vue.prototype.$app = app;
    }
});

import Swal from "sweetalert2";

window.Swal = Swal;
