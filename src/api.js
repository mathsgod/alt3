class API {
    async bodyAddClass(name) {
        var resp = await this.mutation({
            bodyAddClass: {
                __args: {
                    class: name
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error.message;
        }
    }

    async bodyRemoveClass(name) {
        var resp = await this.mutation({
            bodyRemoveClass: {
                __args: {
                    class: name
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error.message;
        }
    }

    async sidebarNavAddClass(name) {
        var resp = await this.mutation({
            sidebarNavAddClass: {
                __args: {
                    class: name
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error.message;
        }
    }

    async sidebarNavRemoveClass(name) {
        var resp = await this.mutation({
            sidebarNavRemoveClass: {
                __args: {
                    class: name
                }
            }
        });
        resp = resp.data;
        if (resp.error) {
            throw resp.error.message;
        }
    }

    mutation(data) {
        return Vue.gql.mutation("api", data);
    }

    query(data) {
        return Vue.gql.query("api", data);
    }
}
window.api = new API();
