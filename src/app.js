import Alert from './alert.js';
class APP {
    constructor() {
        this.alert = new Alert();
    }
}

window.app = new APP();


import Swal from "sweetalert2";

window.Swal = Swal;