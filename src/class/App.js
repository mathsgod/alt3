import Alert from './Alert.js';
class APP {
    constructor(Vue) {
        this.alert = new Alert();
        this.Vue = Vue;
    }

    async mutation(query) {
        var resp = await this.Vue.gql.mutation("api", query);
        resp = resp.data;
        if (resp.error) throw resp.error.message;
        return resp;
    }

    async query(query) {
        var resp = await this.Vue.gql.query("api", query);
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

export default {
    install: (Vue, options) => {
        const app = new APP(Vue);
        window.app = app;
        Vue.prototype.$app = app;
    }
};

