if (!window.fbControls) window.fbControls = [];
window.fbControls.push(function (controlClass, allControlClasses) {
    class controlSelect2 extends allControlClasses.select {
        static get definition() {
            return {
                i18n: {
                    default: 'Select2'
                }
            };
        }

    }

    // register this control for the following types & text subtypes
    controlClass.register('select2', controlSelect2);

});
window.fbControls.push(function (controlClass, allControlClasses) {
    class controlButton2 extends allControlClasses.button {
        static get definition() {
            return {
                i18n: {
                    default: 'button2'
                }
            };
        }

    }


    controlClass.register(['button2'], controlButton2, "button");

});



window.fbControls.push(function (controlClass, allControlClasses) {
    class controlCKEditor extends allControlClasses.textarea {
        static get definition() {
            return {
                i18n: {
                    default: 'ckeditor'
                }
            };
        }



        /**
         * build a text DOM element, supporting other jquery text form-control's
         * @return {Object} DOM Element to be injected into the form.
         */
        build() {
            const { value = '', ...attrs } = this.config;
            this.field = this.markup('textarea', this.parsedHtml(value), attrs);
            return this.field;
        }

        onRender() {
            console.log("onrenlder");
            console.log(this);
            $('#' + this.config.id).val(this.config.value);
        }

    }


    controlClass.register('ckeditor', controlCKEditor);

});
