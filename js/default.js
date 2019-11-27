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

// Example starter JavaScript for disabling form submissions if there are invalid fields
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

function __add_favorite() {
    var label = prompt("請輸入標籤", window.document.title);
    if (label != undefined && label != "") {
        $.post("UI/save/saveFav", {
            layout: {
                label: label,
                link: self.location.pathname + self.location.search
            }
        }).done(function (resp) {
            if (resp.code == 200) {
                window.self.location.reload();
            } else {
                alert("error add fav");
            }
        });
    }
}