import { jsonToGraphQLQuery, VariableType } from 'json-to-graphql-query';
class GQL {

    constructor(Vue) {
        this.Vue = Vue;
    }

    query(url, query) {
        var q = {
            query: query
        };
        return this.Vue.http.post(url, {
            query: jsonToGraphQLQuery(q)
        });
    }

    mutation(url, query) {
        var q = {
            mutation: query
        };
        return this.Vue.http.post(url, {
            query: jsonToGraphQLQuery(q)
        });
    }

    subscription(url, query) {
        var q = {
            subscription: query
        };
        return this.Vue.http.post(url, {
            query: jsonToGraphQLQuery(q)
        });
    }
}

var e = {
    install: (Vue) => {
        Vue.mixin({

        });
        Vue.prototype.$gql = new GQL(Vue);
        Vue.gql = Vue.prototype.$gql;
    }
};


window.jsonToGraphQLQuery = jsonToGraphQLQuery;
window.VariableType = VariableType;

export default e;