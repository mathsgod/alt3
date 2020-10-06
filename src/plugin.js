import Swal from "sweetalert2";
window.Swal = Swal;



Vue = window.Vue;

import httpVueLoader from "http-vue-loader";
window.httpVueLoader = httpVueLoader;
import "x-html-bs4/dist/x-html-bs4.umd.js";

import 'icheck-bootstrap';

import '@fancyapps/fancybox';
import '@fancyapps/fancybox/dist/jquery.fancybox.css';

$.validator.setDefaults({
    ignore: [],
    highlight: function (element) {
        //element.classList.add("is-invalid");
    },
    unhighlight: function (element) {
        //element.classList.remove("is-invalid");
    },
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    var forms = document.querySelectorAll("form");

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        $(form).validate();

        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});



window.__add_favorite = async function () {
    var label = prompt("請輸入標籤", window.document.title);
    if (label != undefined && label != "") {
        var resp = await Vue.gql.mutation("api", {
            addFavorite: {
                __args: {
                    label: label,
                    link: self.location.pathname + self.location.search
                }
            }
        });

        resp = resp.data;
        if (resp.error) {
            alert(resp.error.message);
            return;
        }

        window.self.location.reload();
    }
}