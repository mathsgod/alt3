window.VueDialog = class VueDialog {
    constructor(options = {}) {
        this.options = options;
        this.vm = "";
    }

    open() {
        var id = "_bootbox-" + new Date().getTime();

        this.options.buttons.submit.callback.bind(this.vm);


        let div = document.createElement("div");
        div.id = id;
        div.innerHTML = this.options.el.innerHTML;

        var opt = Object.assign({}, { ...this.options, ...{ el: div } });
        this.vm = new Vue(opt);
        if (opt.init) {
            opt.init = opt.init.bind(this.vm);
        }

        var bb_options = {
            message: "<div></div>",
            centerVertical: true,
            show: false,
            scrollable: true
        };

        bb_options = { ...bb_options, ...this.options };
        if (bb_options.buttons) {

            for (var i in bb_options.buttons) {
                var button = bb_options.buttons[i];

                console.log(button);
                if (button instanceof Function) {
                    button = button.bind(this.vm);
                } else if (button.callback) {
                    button.callback = button.callback.bind(this.vm);
                }
            }
        }

        this.box = bootbox.dialog(bb_options);

        this.box.on("shown.bs.modal", () => {
            this.box.find(".bootbox-body").append(this.vm.$el);
            if (opt.init) {
                opt.init();
            }

        });

        this.box.modal("show");


    }

    close() {
        this.box.modal('hide');
    }
};

