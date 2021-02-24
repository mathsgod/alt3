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

var init_vue = function (element) {

    var nodes = element.querySelectorAll("r-form, r-a, vue, r-table, card");
    nodes.forEach(node => {
        new Vue({
            el: node
        });
    });
}

var observer = new MutationObserver(mutationList => {
    mutationList.forEach(record => {
        init_vue(record.target);
    });
});
observer.observe(document.body, { attributes: false, childList: true, subtree: true });


init_vue(document);