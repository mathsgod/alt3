class API {
    bodyAddClass(name) {
        return this.mutation({
            bodyAddClass: {
                __args: {
                    class: name
                }
            }
        });
    }

    bodyRemoveClass(name) {
        return this.mutation({
            bodyRemoveClass: {
                __args: {
                    class: name
                }
            }
        });

    }

    mutation(data) {
        return Vue.gql.mutation("api", data);
    }

    query(data) {
        return Vue.gql.query("api", data);
    }
}
window.api = new API();
