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