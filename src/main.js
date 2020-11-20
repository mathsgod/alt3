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
    mutationList.forEach(record => {
        let tables = record.target.querySelectorAll("r-table");
        tables.forEach(node => {
            new Vue({
                el: node
            });
        });
    });
});
observer.observe(document.body, { attributes: false, childList: true, subtree: true });