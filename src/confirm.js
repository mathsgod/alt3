//defer
//Confirm
(function () {
    var f = function (el) {
        var msg = el.getAttribute("confirm-msg");
        if (msg == undefined) msg = "Are you sure?";

        el.classList.remove("confirm");
        el.classList.add("_confirm");

        el.addEventListener("click", event => {
            if (!confirm(msg)) {
                event.preventDefault();
            }
        });
    };
    setTimeout(function () {
        document.querySelectorAll(".confirm").forEach(f);
    });
    var observer = new MutationObserver(mutationList => {
        setTimeout(() => {
            document.querySelectorAll(".confirm").forEach(f);
        });

    });
    var config = { attributes: true, childList: true, subtree: true };
    observer.observe(document.body, config);
})();