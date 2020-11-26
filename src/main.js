window.sidebar = new Vue({
    el: "#nav-sidebar",
    data: {
        q: ""
    }, watch: {
        q() {
            this.$children.forEach(v => {
                v.q = this.q;
            });
        }
    }
});

import { WebAuthn } from '../vendor/mathsgod/r-webauthn/src/WebAuthn';
window.WebAuthn = WebAuthn;

import VueDialog from './VueDialog';
window.VueDialog = VueDialog;



var observer = new MutationObserver(mutationList => {
    setTimeout(() => {
        mutationList.forEach(record => {
            let tables = record.target.querySelectorAll("r-table");
            tables.forEach(node => {
                new Vue({
                    el: node
                });
            });

            let cards = record.target.querySelectorAll("card");
            cards.forEach(node => {
                new Vue({
                    el: node
                });
            });
        });
    });
});
observer.observe(document.body, { attributes: false, childList: true, subtree: true });

var forms = document.querySelectorAll("r-form");
forms.forEach(node => {
    new Vue({
        el: node
    });
});

var vues = document.querySelectorAll("vue");
vues.forEach(node => {
    new Vue({
        el: node
    });
});